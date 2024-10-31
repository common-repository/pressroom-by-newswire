<?php

/**
 * Force download link from browser (not in zip)
 */
function pressroom_download_image_link($attachment_id) {
    return site_url('?action=pressroom_download_image&id=' . $attachment_id);
}


/**
 * Image Album block
 * pin_as_image download link
 */
function pin_as_image_download_link($post_id) {
    return site_url('?action=pressroom_view_album&id=' . $post_id . '#TB_iframe=true&width=800&height=500');
}

add_action('wp_loaded', 'pin_as_image_download_callback');
function pin_as_image_download_callback() {

    $post_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : null;

    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

    if ('pressroom_view_album' != $action) {
        return;
    }

    $images = get_children('post_type=attachment&post_mime_type=image&post_parent=' . $post_id);
    echo '<html><head>';
    wp_head();
    echo '<style>.download-table td,.download-table th {padding: 5px; } .download-table a { font-size:11px;} </style>';
    echo '</head><body style="margin: 10px">';
    echo sprintf('<h3 style="font-size: 16px">Click <a href="%s" style="color:red">here </a>to download all the images as Zip file. </h3>', site_url('?action=pressroom_download_album&id=' . $post_id));
    echo '<table class="download-table" width="100%" border="1" cellspacing="5" cellpadding="5" style="background-color: #eee; padding: 5px: margin: ">';
    echo '<tr>';
    echo '  <th>Photo</th>';
    echo '  <th>Meta Data</th>';
    echo '</tr>';

    foreach ($images as $image) {

        echo '<tr>';
        echo '<td width="200" style="text-align:center; vertical-align: top">';
        echo wp_get_attachment_image($image->ID, 'thumbnail');
        echo '</td>';
        echo '<td valign="top" width="auto" style="vertical-alignment: top; font-size: 14px">';
        echo 'Image Url: <input class="flat" type="text" value="' . wp_get_attachment_url($image->ID) . '" style="width:100%; padding: 2px 0">';
        echo '<br>';
        echo sprintf('<a href="%s" target="_blank" style="color: red">Download image</a>', pressroom_download_image_link($image->ID));
        echo ' | ';
        echo sprintf('<a href="%s" target="_blank" style="color:red">View Full Size</a>', wp_get_attachment_url($image->ID));
        echo '<br>';
        echo 'Caption: ' . $image->post_excerpt;
        echo '<br>';
        echo 'Description: ' . $image->post_content;
        echo '<br>';

        echo '</td>';
        echo '<tr>';
    }
    echo '</table></body>';
    echo '</html>';
    exit;
}

/**
 * Image Block - download attachment as zip file
 * Create zip file of images on the fly
 *
 */
add_action('init', 'newswire_download_attachments');
function newswire_download_attachments() {
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
    if ('pressroom_download_album' != $action) {
        return;
    }

    $album_id = intval($_REQUEST['id']);

    //loop through each attachment
    $args = array(
        'post_type' => 'attachment',
        'post_parent' => $album_id,
        'post_mime_type' => 'image',
        'post_status' => 'inherit',
    );
    $image_attachments = get_children($args);

    $files = array();
    $uploads = wp_upload_dir();

    include_once ABSPATH . "wp-admin/includes/class-pclzip.php";
    include_once ABSPATH . "wp-admin/includes/file.php";

    $zipfile = wp_tempnam("album-$album_id") . '.zip';
    $archive = new PclZip($zipfile);

    foreach ($image_attachments as $image) {
        $attachment = get_post_meta($image->ID, '_wp_attached_file', TRUE);
        $filepath = $uploads['basedir'] . '/' . $attachment;
        //read each filepath and add to zip
        if (!file_exists($filepath) || !is_readable($filepath)) {
            continue;
        }

        //$files[] = $filepath;
        $archive->add($filepath, PCLZIP_OPT_REMOVE_ALL_PATH);
    }

    if (file_exists($zipfile)) {
        $archive_size = filesize($zipfile);
    } else {
        $archive_size = 0;
    }

    @set_time_limit(0);
    @ini_set("memory_limit", apply_filters("admin_memory_limit", WP_MAX_MEMORY_LIMIT));

    @ini_set("zlib.output_compression", 0);
    if (function_exists("apache_setenv")) {
        @apache_setenv("no-gzip", "1");
    } while (@ob_end_clean());

    status_header(200); // 200 OK status header.

    header("Content-Encoding: none");
    header("Accept-Ranges: none");
    header("Content-Type: application/zip");
    header("Content-Length: " . $archive_size);
    header("Expires: " . gmdate("D, d M Y H:i:s", strtotime("-1 week")) . " GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    header('Content-Disposition: attachment; filename="' . basename($zipfile) . '"');

    if ($zipfile && is_resource($resource = fopen($zipfile, "rb"))) {

        $_bytes_to_read = $archive_size; // Total bytes we need to read for this file.

        $chunk_size = 2097152;

        while /* We have bytes to read here. */ ($_bytes_to_read) {
            $_bytes_to_read -= ($_reading = ($_bytes_to_read > $chunk_size) ? $chunk_size : $_bytes_to_read);
            echo /* Serve file in chunks (default chunk size is 2MB). */fread($resource, $_reading);
            flush/* Flush each chunk to the browser as it is served (avoids high memory consumption). */();
        }
        fclose/* Close file resource handle. */($resource);
        unset/* Housekeeping. */($_bytes_to_read, $_reading);
    }
    exit; // Clean exit after serving file.

}

/**
 * Download pin_as_image images as zip
 */
add_action('init', 'newswire_pin_as_image_force_download');
function newswire_pin_as_image_force_download() {
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
    if ('pressroom_download_image' != $action) {
        return;
    }

    if (empty($_GET['id'])) {
        header("HTTP/1.0 404 Not Found");
        return;
    }
    $attachment_id = intval($_REQUEST['id']);
    $uploads = wp_upload_dir();
    $attachment = get_post_meta($attachment_id, '_wp_attached_file', TRUE);
    $filepath = $uploads['basedir'] . '/' . $attachment;

    if (!file_exists($filepath) || !is_readable($filepath)) {
        return FALSE;
    }

    //if filename contains folder names
    if (($position = strrpos($attachment, '/', 0)) !== FALSE) {
        $filename = substr($attachment, $position + 1);
    } else {
        $filename = $attachment;
    }

    if (ini_get('zlib.output_compression')) {
        ini_set('zlib.output_compression', 'Off');
    }

    header('Content-Type: application/download');
    header('Content-Disposition: attachment; filename=' . rawurldecode($filename));
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
    header('Cache-control: private');
    header('Pragma: private');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-Length: ' . filesize($filepath));

    if ($filepath = fopen($filepath, 'r')) {
        while (!feof($filepath) && (!connection_aborted())) {
            echo ($buffer = fread($filepath, 524288));
            flush();
        }

        fclose($filepath);
    } else {
        return FALSE;
    }

    exit;
}