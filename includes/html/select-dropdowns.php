<?php 
/**
* @todo :
*  this will be replace by dynamic functions that retrieve information remotely or cached data after syncing
*  
* 
*/
function newswire_publish_date_elements($selected ='') {

    $html = '<div id="publish_date-element" class="form-element">
                           
                           <div class="event_calendar_container" style="display:inline">
                            
                            <button type="button" class="event_calendar "></button>

                            <input type="text" name="publish_date[date]" value="11/4/2013" class="calendar event_calendar" id="newswire_publish_date" readonly="">

                    </div>

                    <select name="publish_date[hour]" id="publish_date-hour">
    <option value="" label=""></option>
    <option value="1" label="1">1</option>
    <option value="2" label="2">2</option>
    <option value="3" label="3">3</option>
    <option value="4" label="4">4</option>
    <option value="5" label="5" selected="selected">5</option>
    <option value="6" label="6">6</option>
    <option value="7" label="7">7</option>
    <option value="8" label="8">8</option>
    <option value="9" label="9">9</option>
    <option value="10" label="10">10</option>
    <option value="11" label="11">11</option>
    <option value="12" label="12">12</option>
</select><select name="publish_date[minute]" id="publish_date-minute">
    <option value="" label=""></option>
    <option value="0" label="00">00</option>
    <option value="1" label="01">01</option>
    <option value="2" label="02">02</option>
    <option value="3" label="03">03</option>
    <option value="4" label="04">04</option>
    <option value="5" label="05">05</option>
    <option value="6" label="06">06</option>
    <option value="7" label="07">07</option>
    <option value="8" label="08">08</option>
    <option value="9" label="09">09</option>
    <option value="10" label="10">10</option>
    <option value="11" label="11">11</option>
    <option value="12" label="12">12</option>
    <option value="13" label="13">13</option>
    <option value="14" label="14">14</option>
    <option value="15" label="15">15</option>
    <option value="16" label="16">16</option>
    <option value="17" label="17">17</option>
    <option value="18" label="18">18</option>
    <option value="19" label="19">19</option>
    <option value="20" label="20">20</option>
    <option value="21" label="21">21</option>
    <option value="22" label="22">22</option>
    <option value="23" label="23">23</option>
    <option value="24" label="24">24</option>
    <option value="25" label="25">25</option>
    <option value="26" label="26">26</option>
    <option value="27" label="27">27</option>
    <option value="28" label="28">28</option>
    <option value="29" label="29">29</option>
    <option value="30" label="30">30</option>
    <option value="31" label="31">31</option>
    <option value="32" label="32">32</option>
    <option value="33" label="33">33</option>
    <option value="34" label="34">34</option>
    <option value="35" label="35">35</option>
    <option value="36" label="36">36</option>
    <option value="37" label="37">37</option>
    <option value="38" label="38">38</option>
    <option value="39" label="39">39</option>
    <option value="40" label="40">40</option>
    <option value="41" label="41">41</option>
    <option value="42" label="42">42</option>
    <option value="43" label="43" selected="selected">43</option>
    <option value="44" label="44">44</option>
    <option value="45" label="45">45</option>
    <option value="46" label="46">46</option>
    <option value="47" label="47">47</option>
    <option value="48" label="48">48</option>
    <option value="49" label="49">49</option>
    <option value="50" label="50">50</option>
    <option value="51" label="51">51</option>
    <option value="52" label="52">52</option>
    <option value="53" label="53">53</option>
    <option value="54" label="54">54</option>
    <option value="55" label="55">55</option>
    <option value="56" label="56">56</option>
    <option value="57" label="57">57</option>
    <option value="58" label="58">58</option>
    <option value="59" label="59">59</option>
</select><select name="publish_date[ampm]" id="publish_date-ampm">
    <option value="" label=""></option>
    <option value="AM" label="AM">AM</option>
    <option value="PM" label="PM" selected="selected">PM</option>
</select></div>';
}
/**
*
*/
function newswire_select_language($selected = 'en_US') {
    $html = '<select name="newswire_data[language]" id="language">
   
    <option value="eng" label="English">English</option>
    <option value="fre" label="French">French</option>
    <option value="ger" label="German">German</option>
    <option value="rus" label="Russian">Russian</option>
    <option value="spa" label="Spanish; Castilian">Spanish; Castilian</option>
    <option value="aar" label="Afar">Afar</option>
    <option value="abk" label="Abkhazian">Abkhazian</option>
    <option value="ace" label="Achinese">Achinese</option>
    <option value="ach" label="Acoli">Acoli</option>
    <option value="ada" label="Adangme">Adangme</option>
    <option value="ady" label="Adyghe; Adygei">Adyghe; Adygei</option>
    <option value="afa" label="Afro-Asiatic languages">Afro-Asiatic languages</option>
    <option value="afh" label="Afrihili">Afrihili</option>
    <option value="afr" label="Afrikaans">Afrikaans</option>
    <option value="ain" label="Ainu">Ainu</option>
    <option value="aka" label="Akan">Akan</option>
    <option value="akk" label="Akkadian">Akkadian</option>
    <option value="alb" label="Albanian">Albanian</option>
    <option value="ale" label="Aleut">Aleut</option>
    <option value="alg" label="Algonquian languages">Algonquian languages</option>
    <option value="alt" label="Southern Altai">Southern Altai</option>
    <option value="amh" label="Amharic">Amharic</option>
    <option value="ang" label="English, Old (ca.450-1100)">English, Old (ca.450-1100)</option>
    <option value="anp" label="Angika">Angika</option>
    <option value="apa" label="Apache languages">Apache languages</option>
    <option value="ara" label="Arabic">Arabic</option>
    <option value="arc" label="Official Aramaic (700-300 BCE); Imperial Aramaic (700-300 BCE)">Official Aramaic (700-300 BCE); Imperial Aramaic (700-300 BCE)</option>
    <option value="arg" label="Aragonese">Aragonese</option>
    <option value="arm" label="Armenian">Armenian</option>
    <option value="arn" label="Mapudungun; Mapuche">Mapudungun; Mapuche</option>
    <option value="arp" label="Arapaho">Arapaho</option>
    <option value="art" label="Artificial languages">Artificial languages</option>
    <option value="arw" label="Arawak">Arawak</option>
    <option value="asm" label="Assamese">Assamese</option>
    <option value="ast" label="Asturian; Bable; Leonese; Asturleonese">Asturian; Bable; Leonese; Asturleonese</option>
    <option value="ath" label="Athapascan languages">Athapascan languages</option>
    <option value="aus" label="Australian languages">Australian languages</option>
    <option value="ava" label="Avaric">Avaric</option>
    <option value="ave" label="Avestan">Avestan</option>
    <option value="awa" label="Awadhi">Awadhi</option>
    <option value="aym" label="Aymara">Aymara</option>
    <option value="aze" label="Azerbaijani">Azerbaijani</option>
    <option value="bad" label="Banda languages">Banda languages</option>
    <option value="bai" label="Bamileke languages">Bamileke languages</option>
    <option value="bak" label="Bashkir">Bashkir</option>
    <option value="bal" label="Baluchi">Baluchi</option>
    <option value="bam" label="Bambara">Bambara</option>
    <option value="ban" label="Balinese">Balinese</option>
    <option value="baq" label="Basque">Basque</option>
    <option value="bas" label="Basa">Basa</option>
    <option value="bat" label="Baltic languages">Baltic languages</option>
    <option value="bej" label="Beja; Bedawiyet">Beja; Bedawiyet</option>
    <option value="bel" label="Belarusian">Belarusian</option>
    <option value="bem" label="Bemba">Bemba</option>
    <option value="ben" label="Bengali">Bengali</option>
    <option value="ber" label="Berber languages">Berber languages</option>
    <option value="bho" label="Bhojpuri">Bhojpuri</option>
    <option value="bih" label="Bihari languages">Bihari languages</option>
    <option value="bik" label="Bikol">Bikol</option>
    <option value="bin" label="Bini; Edo">Bini; Edo</option>
    <option value="bis" label="Bislama">Bislama</option>
    <option value="bla" label="Siksika">Siksika</option>
    <option value="bnt" label="Bantu (Other)">Bantu (Other)</option>
    <option value="bos" label="Bosnian">Bosnian</option>
    <option value="bra" label="Braj">Braj</option>
    <option value="bre" label="Breton">Breton</option>
    <option value="btk" label="Batak languages">Batak languages</option>
    <option value="bua" label="Buriat">Buriat</option>
    <option value="bug" label="Buginese">Buginese</option>
    <option value="bul" label="Bulgarian">Bulgarian</option>
    <option value="bur" label="Burmese">Burmese</option>
    <option value="byn" label="Blin; Bilin">Blin; Bilin</option>
    <option value="cad" label="Caddo">Caddo</option>
    <option value="cai" label="Central American Indian languages">Central American Indian languages</option>
    <option value="car" label="Galibi Carib">Galibi Carib</option>
    <option value="cat" label="Catalan; Valencian">Catalan; Valencian</option>
    <option value="cau" label="Caucasian languages">Caucasian languages</option>
    <option value="ceb" label="Cebuano">Cebuano</option>
    <option value="cel" label="Celtic languages">Celtic languages</option>
    <option value="cha" label="Chamorro">Chamorro</option>
    <option value="chb" label="Chibcha">Chibcha</option>
    <option value="che" label="Chechen">Chechen</option>
    <option value="chg" label="Chagatai">Chagatai</option>
    <option value="zh-cn" label="Simplified Chinese">Simplified Chinese</option>
    <option value="zh-tw" label="Traditional Chinese">Traditional Chinese</option>
    <option value="chk" label="Chuukese">Chuukese</option>
    <option value="chm" label="Mari">Mari</option>
    <option value="chn" label="Chinook jargon">Chinook jargon</option>
    <option value="cho" label="Choctaw">Choctaw</option>
    <option value="chp" label="Chipewyan; Dene Suline">Chipewyan; Dene Suline</option>
    <option value="chr" label="Cherokee">Cherokee</option>
    <option value="chu" label="Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic">Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic</option>
    <option value="chv" label="Chuvash">Chuvash</option>
    <option value="chy" label="Cheyenne">Cheyenne</option>
    <option value="cmc" label="Chamic languages">Chamic languages</option>
    <option value="cop" label="Coptic">Coptic</option>
    <option value="cor" label="Cornish">Cornish</option>
    <option value="cos" label="Corsican">Corsican</option>
    <option value="cpe" label="Creoles and pidgins, English based">Creoles and pidgins, English based</option>
    <option value="cpf" label="Creoles and pidgins, French-based ">Creoles and pidgins, French-based </option>
    <option value="cpp" label="Creoles and pidgins, Portuguese-based ">Creoles and pidgins, Portuguese-based </option>
    <option value="cre" label="Cree">Cree</option>
    <option value="crh" label="Crimean Tatar; Crimean Turkish">Crimean Tatar; Crimean Turkish</option>
    <option value="crp" label="Creoles and pidgins ">Creoles and pidgins </option>
    <option value="csb" label="Kashubian">Kashubian</option>
    <option value="cus" label="Cushitic languages">Cushitic languages</option>
    <option value="cze" label="Czech">Czech</option>
    <option value="dak" label="Dakota">Dakota</option>
    <option value="dan" label="Danish">Danish</option>
    <option value="dar" label="Dargwa">Dargwa</option>
    <option value="day" label="Land Dayak languages">Land Dayak languages</option>
    <option value="del" label="Delaware">Delaware</option>
    <option value="den" label="Slave (Athapascan)">Slave (Athapascan)</option>
    <option value="dgr" label="Dogrib">Dogrib</option>
    <option value="din" label="Dinka">Dinka</option>
    <option value="div" label="Divehi; Dhivehi; Maldivian">Divehi; Dhivehi; Maldivian</option>
    <option value="doi" label="Dogri">Dogri</option>
    <option value="dra" label="Dravidian languages">Dravidian languages</option>
    <option value="dsb" label="Lower Sorbian">Lower Sorbian</option>
    <option value="dua" label="Duala">Duala</option>
    <option value="dum" label="Dutch, Middle (ca.1050-1350)">Dutch, Middle (ca.1050-1350)</option>
    <option value="dut" label="Dutch; Flemish">Dutch; Flemish</option>
    <option value="dyu" label="Dyula">Dyula</option>
    <option value="dzo" label="Dzongkha">Dzongkha</option>
    <option value="efi" label="Efik">Efik</option>
    <option value="egy" label="Egyptian (Ancient)">Egyptian (Ancient)</option>
    <option value="eka" label="Ekajuk">Ekajuk</option>
    <option value="elx" label="Elamite">Elamite</option>
    <option value="enm" label="English, Middle (1100-1500)">English, Middle (1100-1500)</option>
    <option value="epo" label="Esperanto">Esperanto</option>
    <option value="est" label="Estonian">Estonian</option>
    <option value="ewe" label="Ewe">Ewe</option>
    <option value="ewo" label="Ewondo">Ewondo</option>
    <option value="fan" label="Fang">Fang</option>
    <option value="fao" label="Faroese">Faroese</option>
    <option value="fat" label="Fanti">Fanti</option>
    <option value="fij" label="Fijian">Fijian</option>
    <option value="fil" label="Filipino; Pilipino">Filipino; Pilipino</option>
    <option value="fin" label="Finnish">Finnish</option>
    <option value="fiu" label="Finno-Ugrian languages">Finno-Ugrian languages</option>
    <option value="fon" label="Fon">Fon</option>
    <option value="frm" label="French, Middle (ca.1400-1600)">French, Middle (ca.1400-1600)</option>
    <option value="fro" label="French, Old (842-ca.1400)">French, Old (842-ca.1400)</option>
    <option value="frr" label="Northern Frisian">Northern Frisian</option>
    <option value="frs" label="Eastern Frisian">Eastern Frisian</option>
    <option value="fry" label="Western Frisian">Western Frisian</option>
    <option value="ful" label="Fulah">Fulah</option>
    <option value="fur" label="Friulian">Friulian</option>
    <option value="gaa" label="Ga">Ga</option>
    <option value="gay" label="Gayo">Gayo</option>
    <option value="gba" label="Gbaya">Gbaya</option>
    <option value="gem" label="Germanic languages">Germanic languages</option>
    <option value="geo" label="Georgian">Georgian</option>
    <option value="gez" label="Geez">Geez</option>
    <option value="gil" label="Gilbertese">Gilbertese</option>
    <option value="gla" label="Gaelic; Scottish Gaelic">Gaelic; Scottish Gaelic</option>
    <option value="gle" label="Irish">Irish</option>
    <option value="glg" label="Galician">Galician</option>
    <option value="glv" label="Manx">Manx</option>
    <option value="gmh" label="German, Middle High (ca.1050-1500)">German, Middle High (ca.1050-1500)</option>
    <option value="goh" label="German, Old High (ca.750-1050)">German, Old High (ca.750-1050)</option>
    <option value="gon" label="Gondi">Gondi</option>
    <option value="gor" label="Gorontalo">Gorontalo</option>
    <option value="got" label="Gothic">Gothic</option>
    <option value="grb" label="Grebo">Grebo</option>
    <option value="grc" label="Greek, Ancient (to 1453)">Greek, Ancient (to 1453)</option>
    <option value="gre" label="Greek, Modern (1453-)">Greek, Modern (1453-)</option>
    <option value="grn" label="Guarani">Guarani</option>
    <option value="gsw" label="Swiss German; Alemannic; Alsatian">Swiss German; Alemannic; Alsatian</option>
    <option value="guj" label="Gujarati">Gujarati</option>
    <option value="gwi" label="Gwich\'in">Gwich\'in</option>
    <option value="hai" label="Haida">Haida</option>
    <option value="hat" label="Haitian; Haitian Creole">Haitian; Haitian Creole</option>
    <option value="hau" label="Hausa">Hausa</option>
    <option value="haw" label="Hawaiian">Hawaiian</option>
    <option value="heb" label="Hebrew">Hebrew</option>
    <option value="her" label="Herero">Herero</option>
    <option value="hil" label="Hiligaynon">Hiligaynon</option>
    <option value="him" label="Himachali languages; Western Pahari languages">Himachali languages; Western Pahari languages</option>
    <option value="hin" label="Hindi">Hindi</option>
    <option value="hit" label="Hittite">Hittite</option>
    <option value="hmn" label="Hmong; Mong">Hmong; Mong</option>
    <option value="hmo" label="Hiri Motu">Hiri Motu</option>
    <option value="hrv" label="Croatian">Croatian</option>
    <option value="hsb" label="Upper Sorbian">Upper Sorbian</option>
    <option value="hun" label="Hungarian">Hungarian</option>
    <option value="hup" label="Hupa">Hupa</option>
    <option value="iba" label="Iban">Iban</option>
    <option value="ibo" label="Igbo">Igbo</option>
    <option value="ice" label="Icelandic">Icelandic</option>
    <option value="ido" label="Ido">Ido</option>
    <option value="iii" label="Sichuan Yi; Nuosu">Sichuan Yi; Nuosu</option>
    <option value="ijo" label="Ijo languages">Ijo languages</option>
    <option value="iku" label="Inuktitut">Inuktitut</option>
    <option value="ile" label="Interlingue; Occidental">Interlingue; Occidental</option>
    <option value="ilo" label="Iloko">Iloko</option>
    <option value="ina" label="Interlingua (International Auxiliary Language Association)">Interlingua (International Auxiliary Language Association)</option>
    <option value="inc" label="Indic languages">Indic languages</option>
    <option value="ind" label="Indonesian">Indonesian</option>
    <option value="ine" label="Indo-European languages">Indo-European languages</option>
    <option value="inh" label="Ingush">Ingush</option>
    <option value="ipk" label="Inupiaq">Inupiaq</option>
    <option value="ira" label="Iranian languages">Iranian languages</option>
    <option value="iro" label="Iroquoian languages">Iroquoian languages</option>
    <option value="ita" label="Italian">Italian</option>
    <option value="jav" label="Javanese">Javanese</option>
    <option value="jbo" label="Lojban">Lojban</option>
    <option value="jpn" label="Japanese">Japanese</option>
    <option value="jpr" label="Judeo-Persian">Judeo-Persian</option>
    <option value="jrb" label="Judeo-Arabic">Judeo-Arabic</option>
    <option value="kaa" label="Kara-Kalpak">Kara-Kalpak</option>
    <option value="kab" label="Kabyle">Kabyle</option>
    <option value="kac" label="Kachin; Jingpho">Kachin; Jingpho</option>
    <option value="kal" label="Kalaallisut; Greenlandic">Kalaallisut; Greenlandic</option>
    <option value="kam" label="Kamba">Kamba</option>
    <option value="kan" label="Kannada">Kannada</option>
    <option value="kar" label="Karen languages">Karen languages</option>
    <option value="kas" label="Kashmiri">Kashmiri</option>
    <option value="kau" label="Kanuri">Kanuri</option>
    <option value="kaw" label="Kawi">Kawi</option>
    <option value="kaz" label="Kazakh">Kazakh</option>
    <option value="kbd" label="Kabardian">Kabardian</option>
    <option value="kha" label="Khasi">Khasi</option>
    <option value="khi" label="Khoisan languages">Khoisan languages</option>
    <option value="khm" label="Central Khmer">Central Khmer</option>
    <option value="kho" label="Khotanese; Sakan">Khotanese; Sakan</option>
    <option value="kik" label="Kikuyu; Gikuyu">Kikuyu; Gikuyu</option>
    <option value="kin" label="Kinyarwanda">Kinyarwanda</option>
    <option value="kir" label="Kirghiz; Kyrgyz">Kirghiz; Kyrgyz</option>
    <option value="kmb" label="Kimbundu">Kimbundu</option>
    <option value="kok" label="Konkani">Konkani</option>
    <option value="kom" label="Komi">Komi</option>
    <option value="kon" label="Kongo">Kongo</option>
    <option value="kor" label="Korean">Korean</option>
    <option value="kos" label="Kosraean">Kosraean</option>
    <option value="kpe" label="Kpelle">Kpelle</option>
    <option value="krc" label="Karachay-Balkar">Karachay-Balkar</option>
    <option value="krl" label="Karelian">Karelian</option>
    <option value="kro" label="Kru languages">Kru languages</option>
    <option value="kru" label="Kurukh">Kurukh</option>
    <option value="kua" label="Kuanyama; Kwanyama">Kuanyama; Kwanyama</option>
    <option value="kum" label="Kumyk">Kumyk</option>
    <option value="kur" label="Kurdish">Kurdish</option>
    <option value="kut" label="Kutenai">Kutenai</option>
    <option value="lad" label="Ladino">Ladino</option>
    <option value="lah" label="Lahnda">Lahnda</option>
    <option value="lam" label="Lamba">Lamba</option>
    <option value="lao" label="Lao">Lao</option>
    <option value="lat" label="Latin">Latin</option>
    <option value="lav" label="Latvian">Latvian</option>
    <option value="lez" label="Lezghian">Lezghian</option>
    <option value="lim" label="Limburgan; Limburger; Limburgish">Limburgan; Limburger; Limburgish</option>
    <option value="lin" label="Lingala">Lingala</option>
    <option value="lit" label="Lithuanian">Lithuanian</option>
    <option value="lol" label="Mongo">Mongo</option>
    <option value="loz" label="Lozi">Lozi</option>
    <option value="ltz" label="Luxembourgish; Letzeburgesch">Luxembourgish; Letzeburgesch</option>
    <option value="lua" label="Luba-Lulua">Luba-Lulua</option>
    <option value="lub" label="Luba-Katanga">Luba-Katanga</option>
    <option value="lug" label="Ganda">Ganda</option>
    <option value="lui" label="Luiseno">Luiseno</option>
    <option value="lun" label="Lunda">Lunda</option>
    <option value="luo" label="Luo (Kenya and Tanzania)">Luo (Kenya and Tanzania)</option>
    <option value="lus" label="Lushai">Lushai</option>
    <option value="mac" label="Macedonian">Macedonian</option>
    <option value="mad" label="Madurese">Madurese</option>
    <option value="mag" label="Magahi">Magahi</option>
    <option value="mah" label="Marshallese">Marshallese</option>
    <option value="mai" label="Maithili">Maithili</option>
    <option value="mak" label="Makasar">Makasar</option>
    <option value="mal" label="Malayalam">Malayalam</option>
    <option value="man" label="Mandingo">Mandingo</option>
    <option value="mao" label="Maori">Maori</option>
    <option value="map" label="Austronesian languages">Austronesian languages</option>
    <option value="mar" label="Marathi">Marathi</option>
    <option value="mas" label="Masai">Masai</option>
    <option value="may" label="Malay">Malay</option>
    <option value="mdf" label="Moksha">Moksha</option>
    <option value="mdr" label="Mandar">Mandar</option>
    <option value="men" label="Mende">Mende</option>
    <option value="mga" label="Irish, Middle (900-1200)">Irish, Middle (900-1200)</option>
    <option value="mic" label="Mi\'kmaq; Micmac">Mi\'kmaq; Micmac</option>
    <option value="min" label="Minangkabau">Minangkabau</option>
    <option value="mis" label="Uncoded languages">Uncoded languages</option>
    <option value="mkh" label="Mon-Khmer languages">Mon-Khmer languages</option>
    <option value="mlg" label="Malagasy">Malagasy</option>
    <option value="mlt" label="Maltese">Maltese</option>
    <option value="mnc" label="Manchu">Manchu</option>
    <option value="mni" label="Manipuri">Manipuri</option>
    <option value="mno" label="Manobo languages">Manobo languages</option>
    <option value="moh" label="Mohawk">Mohawk</option>
    <option value="mon" label="Mongolian">Mongolian</option>
    <option value="mos" label="Mossi">Mossi</option>
    <option value="mul" label="Multiple languages">Multiple languages</option>
    <option value="mun" label="Munda languages">Munda languages</option>
    <option value="mus" label="Creek">Creek</option>
    <option value="mwl" label="Mirandese">Mirandese</option>
    <option value="mwr" label="Marwari">Marwari</option>
    <option value="myn" label="Mayan languages">Mayan languages</option>
    <option value="myv" label="Erzya">Erzya</option>
    <option value="nah" label="Nahuatl languages">Nahuatl languages</option>
    <option value="nai" label="North American Indian languages">North American Indian languages</option>
    <option value="nap" label="Neapolitan">Neapolitan</option>
    <option value="nau" label="Nauru">Nauru</option>
    <option value="nav" label="Navajo; Navaho">Navajo; Navaho</option>
    <option value="nbl" label="Ndebele, South; South Ndebele">Ndebele, South; South Ndebele</option>
    <option value="nde" label="Ndebele, North; North Ndebele">Ndebele, North; North Ndebele</option>
    <option value="ndo" label="Ndonga">Ndonga</option>
    <option value="nds" label="Low German; Low Saxon; German, Low; Saxon, Low">Low German; Low Saxon; German, Low; Saxon, Low</option>
    <option value="nep" label="Nepali">Nepali</option>
    <option value="new" label="Nepal Bhasa; Newari">Nepal Bhasa; Newari</option>
    <option value="nia" label="Nias">Nias</option>
    <option value="nic" label="Niger-Kordofanian languages">Niger-Kordofanian languages</option>
    <option value="niu" label="Niuean">Niuean</option>
    <option value="nno" label="Norwegian Nynorsk; Nynorsk, Norwegian">Norwegian Nynorsk; Nynorsk, Norwegian</option>
    <option value="nob" label="Bokmål, Norwegian; Norwegian Bokmål">Bokmål, Norwegian; Norwegian Bokmål</option>
    <option value="nog" label="Nogai">Nogai</option>
    <option value="non" label="Norse, Old">Norse, Old</option>
    <option value="nor" label="Norwegian">Norwegian</option>
    <option value="nqo" label="N\'Ko">N\'Ko</option>
    <option value="nso" label="Pedi; Sepedi; Northern Sotho">Pedi; Sepedi; Northern Sotho</option>
    <option value="nub" label="Nubian languages">Nubian languages</option>
    <option value="nwc" label="Classical Newari; Old Newari; Classical Nepal Bhasa">Classical Newari; Old Newari; Classical Nepal Bhasa</option>
    <option value="nya" label="Chichewa; Chewa; Nyanja">Chichewa; Chewa; Nyanja</option>
    <option value="nym" label="Nyamwezi">Nyamwezi</option>
    <option value="nyn" label="Nyankole">Nyankole</option>
    <option value="nyo" label="Nyoro">Nyoro</option>
    <option value="nzi" label="Nzima">Nzima</option>
    <option value="oci" label="Occitan (post 1500); Provençal">Occitan (post 1500); Provençal</option>
    <option value="oji" label="Ojibwa">Ojibwa</option>
    <option value="ori" label="Oriya">Oriya</option>
    <option value="orm" label="Oromo">Oromo</option>
    <option value="osa" label="Osage">Osage</option>
    <option value="oss" label="Ossetian; Ossetic">Ossetian; Ossetic</option>
    <option value="ota" label="Turkish, Ottoman (1500-1928)">Turkish, Ottoman (1500-1928)</option>
    <option value="oto" label="Otomian languages">Otomian languages</option>
    <option value="paa" label="Papuan languages">Papuan languages</option>
    <option value="pag" label="Pangasinan">Pangasinan</option>
    <option value="pal" label="Pahlavi">Pahlavi</option>
    <option value="pam" label="Pampanga; Kapampangan">Pampanga; Kapampangan</option>
    <option value="pan" label="Panjabi; Punjabi">Panjabi; Punjabi</option>
    <option value="pap" label="Papiamento">Papiamento</option>
    <option value="pau" label="Palauan">Palauan</option>
    <option value="peo" label="Persian, Old (ca.600-400 B.C.)">Persian, Old (ca.600-400 B.C.)</option>
    <option value="per" label="Persian">Persian</option>
    <option value="phi" label="Philippine languages">Philippine languages</option>
    <option value="phn" label="Phoenician">Phoenician</option>
    <option value="pli" label="Pali">Pali</option>
    <option value="pol" label="Polish">Polish</option>
    <option value="pon" label="Pohnpeian">Pohnpeian</option>
    <option value="por" label="Portuguese">Portuguese</option>
    <option value="pra" label="Prakrit languages">Prakrit languages</option>
    <option value="pro" label="Provençal, Old (to 1500)">Provençal, Old (to 1500)</option>
    <option value="pus" label="Pushto; Pashto">Pushto; Pashto</option>
    <option value="qaa-qtz" label="Reserved for local use">Reserved for local use</option>
    <option value="que" label="Quechua">Quechua</option>
    <option value="raj" label="Rajasthani">Rajasthani</option>
    <option value="rap" label="Rapanui">Rapanui</option>
    <option value="rar" label="Rarotongan; Cook Islands Maori">Rarotongan; Cook Islands Maori</option>
    <option value="roa" label="Romance languages">Romance languages</option>
    <option value="roh" label="Romansh">Romansh</option>
    <option value="rom" label="Romany">Romany</option>
    <option value="rum" label="Romanian; Moldavian; Moldovan">Romanian; Moldavian; Moldovan</option>
    <option value="run" label="Rundi">Rundi</option>
    <option value="rup" label="Aromanian; Arumanian; Macedo-Romanian">Aromanian; Arumanian; Macedo-Romanian</option>
    <option value="sad" label="Sandawe">Sandawe</option>
    <option value="sag" label="Sango">Sango</option>
    <option value="sah" label="Yakut">Yakut</option>
    <option value="sai" label="South American Indian (Other)">South American Indian (Other)</option>
    <option value="sal" label="Salishan languages">Salishan languages</option>
    <option value="sam" label="Samaritan Aramaic">Samaritan Aramaic</option>
    <option value="san" label="Sanskrit">Sanskrit</option>
    <option value="sas" label="Sasak">Sasak</option>
    <option value="sat" label="Santali">Santali</option>
    <option value="scn" label="Sicilian">Sicilian</option>
    <option value="sco" label="Scots">Scots</option>
    <option value="sel" label="Selkup">Selkup</option>
    <option value="sem" label="Semitic languages">Semitic languages</option>
    <option value="sga" label="Irish, Old (to 900)">Irish, Old (to 900)</option>
    <option value="sgn" label="Sign Languages">Sign Languages</option>
    <option value="shn" label="Shan">Shan</option>
    <option value="sid" label="Sidamo">Sidamo</option>
    <option value="sin" label="Sinhala; Sinhalese">Sinhala; Sinhalese</option>
    <option value="sio" label="Siouan languages">Siouan languages</option>
    <option value="sit" label="Sino-Tibetan languages">Sino-Tibetan languages</option>
    <option value="sla" label="Slavic languages">Slavic languages</option>
    <option value="slo" label="Slovak">Slovak</option>
    <option value="slv" label="Slovenian">Slovenian</option>
    <option value="sma" label="Southern Sami">Southern Sami</option>
    <option value="sme" label="Northern Sami">Northern Sami</option>
    <option value="smi" label="Sami languages">Sami languages</option>
    <option value="smj" label="Lule Sami">Lule Sami</option>
    <option value="smn" label="Inari Sami">Inari Sami</option>
    <option value="smo" label="Samoan">Samoan</option>
    <option value="sms" label="Skolt Sami">Skolt Sami</option>
    <option value="sna" label="Shona">Shona</option>
    <option value="snd" label="Sindhi">Sindhi</option>
    <option value="snk" label="Soninke">Soninke</option>
    <option value="sog" label="Sogdian">Sogdian</option>
    <option value="som" label="Somali">Somali</option>
    <option value="son" label="Songhai languages">Songhai languages</option>
    <option value="sot" label="Sotho, Southern">Sotho, Southern</option>
    <option value="srd" label="Sardinian">Sardinian</option>
    <option value="srn" label="Sranan Tongo">Sranan Tongo</option>
    <option value="srp" label="Serbian">Serbian</option>
    <option value="srr" label="Serer">Serer</option>
    <option value="ssa" label="Nilo-Saharan languages">Nilo-Saharan languages</option>
    <option value="ssw" label="Swati">Swati</option>
    <option value="suk" label="Sukuma">Sukuma</option>
    <option value="sun" label="Sundanese">Sundanese</option>
    <option value="sus" label="Susu">Susu</option>
    <option value="sux" label="Sumerian">Sumerian</option>
    <option value="swa" label="Swahili">Swahili</option>
    <option value="swe" label="Swedish">Swedish</option>
    <option value="syc" label="Classical Syriac">Classical Syriac</option>
    <option value="syr" label="Syriac">Syriac</option>
    <option value="tah" label="Tahitian">Tahitian</option>
    <option value="tai" label="Tai languages">Tai languages</option>
    <option value="tam" label="Tamil">Tamil</option>
    <option value="tat" label="Tatar">Tatar</option>
    <option value="tel" label="Telugu">Telugu</option>
    <option value="tem" label="Timne">Timne</option>
    <option value="ter" label="Tereno">Tereno</option>
    <option value="tet" label="Tetum">Tetum</option>
    <option value="tgk" label="Tajik">Tajik</option>
    <option value="tgl" label="Tagalog">Tagalog</option>
    <option value="tha" label="Thai">Thai</option>
    <option value="tib" label="Tibetan">Tibetan</option>
    <option value="tig" label="Tigre">Tigre</option>
    <option value="tir" label="Tigrinya">Tigrinya</option>
    <option value="tiv" label="Tiv">Tiv</option>
    <option value="tkl" label="Tokelau">Tokelau</option>
    <option value="tlh" label="Klingon; tlhIngan-Hol">Klingon; tlhIngan-Hol</option>
    <option value="tli" label="Tlingit">Tlingit</option>
    <option value="tmh" label="Tamashek">Tamashek</option>
    <option value="tog" label="Tonga (Nyasa)">Tonga (Nyasa)</option>
    <option value="ton" label="Tonga (Tonga Islands)">Tonga (Tonga Islands)</option>
    <option value="tpi" label="Tok Pisin">Tok Pisin</option>
    <option value="tsi" label="Tsimshian">Tsimshian</option>
    <option value="tsn" label="Tswana">Tswana</option>
    <option value="tso" label="Tsonga">Tsonga</option>
    <option value="tuk" label="Turkmen">Turkmen</option>
    <option value="tum" label="Tumbuka">Tumbuka</option>
    <option value="tup" label="Tupi languages">Tupi languages</option>
    <option value="tur" label="Turkish">Turkish</option>
    <option value="tut" label="Altaic languages">Altaic languages</option>
    <option value="tvl" label="Tuvalu">Tuvalu</option>
    <option value="twi" label="Twi">Twi</option>
    <option value="tyv" label="Tuvinian">Tuvinian</option>
    <option value="udm" label="Udmurt">Udmurt</option>
    <option value="uga" label="Ugaritic">Ugaritic</option>
    <option value="uig" label="Uighur; Uyghur">Uighur; Uyghur</option>
    <option value="ukr" label="Ukrainian">Ukrainian</option>
    <option value="umb" label="Umbundu">Umbundu</option>
    <option value="und" label="Undetermined">Undetermined</option>
    <option value="urd" label="Urdu">Urdu</option>
    <option value="uzb" label="Uzbek">Uzbek</option>
    <option value="vai" label="Vai">Vai</option>
    <option value="ven" label="Venda">Venda</option>
    <option value="vie" label="Vietnamese">Vietnamese</option>
    <option value="vol" label="Volapük">Volapük</option>
    <option value="vot" label="Votic">Votic</option>
    <option value="wak" label="Wakashan languages">Wakashan languages</option>
    <option value="wal" label="Walamo">Walamo</option>
    <option value="war" label="Waray">Waray</option>
    <option value="was" label="Washo">Washo</option>
    <option value="wel" label="Welsh">Welsh</option>
    <option value="wen" label="Sorbian languages">Sorbian languages</option>
    <option value="wln" label="Walloon">Walloon</option>
    <option value="wol" label="Wolof">Wolof</option>
    <option value="xal" label="Kalmyk; Oirat">Kalmyk; Oirat</option>
    <option value="xho" label="Xhosa">Xhosa</option>
    <option value="yao" label="Yao">Yao</option>
    <option value="yap" label="Yapese">Yapese</option>
    <option value="yid" label="Yiddish">Yiddish</option>
    <option value="yor" label="Yoruba">Yoruba</option>
    <option value="ypk" label="Yupik languages">Yupik languages</option>
    <option value="zap" label="Zapotec">Zapotec</option>
    <option value="zbl" label="Blissymbols; Blissymbolics; Bliss">Blissymbols; Blissymbolics; Bliss</option>
    <option value="zen" label="Zenaga">Zenaga</option>
    <option value="zgh" label="Standard Moroccan Tamazight">Standard Moroccan Tamazight</option>
    <option value="zha" label="Zhuang; Chuang">Zhuang; Chuang</option>
    <option value="znd" label="Zande languages">Zande languages</option>
    <option value="zul" label="Zulu">Zulu</option>
    <option value="zun" label="Zuni">Zuni</option>
    <option value="zxx" label="No linguistic content; Not applicable">No linguistic content; Not applicable</option>
    <option value="zza" label="Zaza; Dimili; Dimli; Kirdki; Kirmanjki; Zazaki">Zaza; Dimili; Dimli; Kirdki; Kirmanjki; Zazaki</option>

</select>';

if ( $selected == ''){
    return $html;
} else {    
    //selected="selected"
    $html =  str_replace('selected="selected"', '', $html);
    $html =  str_replace('<option value="'.$selected.'" ', '<option value="'.$selected.'" selected="selected" ', $html);
    return $html;

}

return $html;
}
/**
*
*/
function newswire_select_countries($selected = 'US') {
    
    $html = '<select name="newswire_data[company_country]" id="company_country">
    <option value="none" label="--None--">--None--</option>
    <option value="AF" label="Afghanistan">Afghanistan</option>
    <option value="AL" label="Albania">Albania</option>
    <option value="DZ" label="Algeria">Algeria</option>
    <option value="AS" label="American Samoa">American Samoa</option>
    <option value="AD" label="Andorra">Andorra</option>
    <option value="AO" label="Angola">Angola</option>
    <option value="AI" label="Anguilla">Anguilla</option>
    <option value="AQ" label="Antarctica">Antarctica</option>
    <option value="AG" label="Antigua and Barbuda">Antigua and Barbuda</option>
    <option value="AR" label="Argentina">Argentina</option>
    <option value="AM" label="Armenia">Armenia</option>
    <option value="AW" label="Aruba">Aruba</option>
    <option value="AU" label="Australia">Australia</option>
    <option value="AT" label="Austria">Austria</option>
    <option value="AZ" label="Azerbaijan">Azerbaijan</option>
    <option value="BS" label="Bahamas">Bahamas</option>
    <option value="BH" label="Bahrain">Bahrain</option>
    <option value="BD" label="Bangladesh">Bangladesh</option>
    <option value="BB" label="Barbados">Barbados</option>
    <option value="BY" label="Belarus">Belarus</option>
    <option value="BE" label="Belgium">Belgium</option>
    <option value="BZ" label="Belize">Belize</option>
    <option value="BJ" label="Benin">Benin</option>
    <option value="BM" label="Bermuda">Bermuda</option>
    <option value="BT" label="Bhutan">Bhutan</option>
    <option value="BO" label="Bolivia">Bolivia</option>
    <option value="BA" label="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
    <option value="BW" label="Botswana">Botswana</option>
    <option value="BV" label="Bouvet Island">Bouvet Island</option>
    <option value="BR" label="Brazil">Brazil</option>
    <option value="BQ" label="British Antarctic Territory">British Antarctic Territory</option>
    <option value="IO" label="British Indian Ocean Territory">British Indian Ocean Territory</option>
    <option value="VG" label="British Virgin Islands">British Virgin Islands</option>
    <option value="BN" label="Brunei">Brunei</option>
    <option value="BG" label="Bulgaria">Bulgaria</option>
    <option value="BF" label="Burkina Faso">Burkina Faso</option>
    <option value="BI" label="Burundi">Burundi</option>
    <option value="KH" label="Cambodia">Cambodia</option>
    <option value="CM" label="Cameroon">Cameroon</option>
    <option value="CA" label="Canada">Canada</option>
    <option value="CT" label="Canton and Enderbury Islands">Canton and Enderbury Islands</option>
    <option value="CV" label="Cape Verde">Cape Verde</option>
    <option value="KY" label="Cayman Islands">Cayman Islands</option>
    <option value="CF" label="Central African Republic">Central African Republic</option>
    <option value="TD" label="Chad">Chad</option>
    <option value="CL" label="Chile">Chile</option>
    <option value="CN" label="China">China</option>
    <option value="CX" label="Christmas Island">Christmas Island</option>
    <option value="CC" label="Cocos [Keeling] Islands">Cocos [Keeling] Islands</option>
    <option value="CO" label="Colombia">Colombia</option>
    <option value="KM" label="Comoros">Comoros</option>
    <option value="CG" label="Congo - Brazzaville">Congo - Brazzaville</option>
    <option value="CD" label="Congo - Kinshasa">Congo - Kinshasa</option>
    <option value="CK" label="Cook Islands">Cook Islands</option>
    <option value="CR" label="Costa Rica">Costa Rica</option>
    <option value="HR" label="Croatia">Croatia</option>
    <option value="CU" label="Cuba">Cuba</option>
    <option value="CY" label="Cyprus">Cyprus</option>
    <option value="CZ" label="Czech Republic">Czech Republic</option>
    <option value="CI" label="Côte d’Ivoire">Côte d’Ivoire</option>
    <option value="DK" label="Denmark">Denmark</option>
    <option value="DJ" label="Djibouti">Djibouti</option>
    <option value="DM" label="Dominica">Dominica</option>
    <option value="DO" label="Dominican Republic">Dominican Republic</option>
    <option value="NQ" label="Dronning Maud Land">Dronning Maud Land</option>
    <option value="DD" label="East Germany">East Germany</option>
    <option value="EC" label="Ecuador">Ecuador</option>
    <option value="EG" label="Egypt">Egypt</option>
    <option value="SV" label="El Salvador">El Salvador</option>
    <option value="GQ" label="Equatorial Guinea">Equatorial Guinea</option>
    <option value="ER" label="Eritrea">Eritrea</option>
    <option value="EE" label="Estonia">Estonia</option>
    <option value="ET" label="Ethiopia">Ethiopia</option>
    <option value="FK" label="Falkland Islands">Falkland Islands</option>
    <option value="FO" label="Faroe Islands">Faroe Islands</option>
    <option value="FJ" label="Fiji">Fiji</option>
    <option value="FI" label="Finland">Finland</option>
    <option value="FR" label="France">France</option>
    <option value="GF" label="French Guiana">French Guiana</option>
    <option value="PF" label="French Polynesia">French Polynesia</option>
    <option value="TF" label="French Southern Territories">French Southern Territories</option>
    <option value="FQ" label="French Southern and Antarctic Territories">French Southern and Antarctic Territories</option>
    <option value="GA" label="Gabon">Gabon</option>
    <option value="GM" label="Gambia">Gambia</option>
    <option value="GE" label="Georgia">Georgia</option>
    <option value="DE" label="Germany">Germany</option>
    <option value="GH" label="Ghana">Ghana</option>
    <option value="GI" label="Gibraltar">Gibraltar</option>
    <option value="GR" label="Greece">Greece</option>
    <option value="GL" label="Greenland">Greenland</option>
    <option value="GD" label="Grenada">Grenada</option>
    <option value="GP" label="Guadeloupe">Guadeloupe</option>
    <option value="GU" label="Guam">Guam</option>
    <option value="GT" label="Guatemala">Guatemala</option>
    <option value="GG" label="Guernsey">Guernsey</option>
    <option value="GN" label="Guinea">Guinea</option>
    <option value="GW" label="Guinea-Bissau">Guinea-Bissau</option>
    <option value="GY" label="Guyana">Guyana</option>
    <option value="HT" label="Haiti">Haiti</option>
    <option value="HM" label="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
    <option value="HN" label="Honduras">Honduras</option>
    <option value="HK" label="Hong Kong SAR China">Hong Kong SAR China</option>
    <option value="HU" label="Hungary">Hungary</option>
    <option value="IS" label="Iceland">Iceland</option>
    <option value="IN" label="India">India</option>
    <option value="ID" label="Indonesia">Indonesia</option>
    <option value="IR" label="Iran">Iran</option>
    <option value="IQ" label="Iraq">Iraq</option>
    <option value="IE" label="Ireland">Ireland</option>
    <option value="IM" label="Isle of Man">Isle of Man</option>
    <option value="IL" label="Israel">Israel</option>
    <option value="IT" label="Italy">Italy</option>
    <option value="JM" label="Jamaica">Jamaica</option>
    <option value="JP" label="Japan">Japan</option>
    <option value="JE" label="Jersey">Jersey</option>
    <option value="JT" label="Johnston Island">Johnston Island</option>
    <option value="JO" label="Jordan">Jordan</option>
    <option value="KZ" label="Kazakhstan">Kazakhstan</option>
    <option value="KE" label="Kenya">Kenya</option>
    <option value="KI" label="Kiribati">Kiribati</option>
    <option value="KW" label="Kuwait">Kuwait</option>
    <option value="KG" label="Kyrgyzstan">Kyrgyzstan</option>
    <option value="LA" label="Laos">Laos</option>
    <option value="LV" label="Latvia">Latvia</option>
    <option value="LB" label="Lebanon">Lebanon</option>
    <option value="LS" label="Lesotho">Lesotho</option>
    <option value="LR" label="Liberia">Liberia</option>
    <option value="LY" label="Libya">Libya</option>
    <option value="LI" label="Liechtenstein">Liechtenstein</option>
    <option value="LT" label="Lithuania">Lithuania</option>
    <option value="LU" label="Luxembourg">Luxembourg</option>
    <option value="MO" label="Macau SAR China">Macau SAR China</option>
    <option value="MK" label="Macedonia">Macedonia</option>
    <option value="MG" label="Madagascar">Madagascar</option>
    <option value="MW" label="Malawi">Malawi</option>
    <option value="MY" label="Malaysia">Malaysia</option>
    <option value="MV" label="Maldives">Maldives</option>
    <option value="ML" label="Mali">Mali</option>
    <option value="MT" label="Malta">Malta</option>
    <option value="MH" label="Marshall Islands">Marshall Islands</option>
    <option value="MQ" label="Martinique">Martinique</option>
    <option value="MR" label="Mauritania">Mauritania</option>
    <option value="MU" label="Mauritius">Mauritius</option>
    <option value="YT" label="Mayotte">Mayotte</option>
    <option value="FX" label="Metropolitan France">Metropolitan France</option>
    <option value="MX" label="Mexico">Mexico</option>
    <option value="FM" label="Micronesia">Micronesia</option>
    <option value="MI" label="Midway Islands">Midway Islands</option>
    <option value="MD" label="Moldova">Moldova</option>
    <option value="MC" label="Monaco">Monaco</option>
    <option value="MN" label="Mongolia">Mongolia</option>
    <option value="ME" label="Montenegro">Montenegro</option>
    <option value="MS" label="Montserrat">Montserrat</option>
    <option value="MA" label="Morocco">Morocco</option>
    <option value="MZ" label="Mozambique">Mozambique</option>
    <option value="MM" label="Myanmar [Burma]">Myanmar [Burma]</option>
    <option value="NA" label="Namibia">Namibia</option>
    <option value="NR" label="Nauru">Nauru</option>
    <option value="NP" label="Nepal">Nepal</option>
    <option value="NL" label="Netherlands">Netherlands</option>
    <option value="AN" label="Netherlands Antilles">Netherlands Antilles</option>
    <option value="NT" label="Neutral Zone">Neutral Zone</option>
    <option value="NC" label="New Caledonia">New Caledonia</option>
    <option value="NZ" label="New Zealand">New Zealand</option>
    <option value="NI" label="Nicaragua">Nicaragua</option>
    <option value="NE" label="Niger">Niger</option>
    <option value="NG" label="Nigeria">Nigeria</option>
    <option value="NU" label="Niue">Niue</option>
    <option value="NF" label="Norfolk Island">Norfolk Island</option>
    <option value="KP" label="North Korea">North Korea</option>
    <option value="VD" label="North Vietnam">North Vietnam</option>
    <option value="MP" label="Northern Mariana Islands">Northern Mariana Islands</option>
    <option value="NO" label="Norway">Norway</option>
    <option value="OM" label="Oman">Oman</option>
    <option value="PC" label="Pacific Islands Trust Territory">Pacific Islands Trust Territory</option>
    <option value="PK" label="Pakistan">Pakistan</option>
    <option value="PW" label="Palau">Palau</option>
    <option value="PS" label="Palestinian Territories">Palestinian Territories</option>
    <option value="PA" label="Panama">Panama</option>
    <option value="PZ" label="Panama Canal Zone">Panama Canal Zone</option>
    <option value="PG" label="Papua New Guinea">Papua New Guinea</option>
    <option value="PY" label="Paraguay">Paraguay</option>
    <option value="YD" label="People\'s Democratic Republic of Yemen">People\'s Democratic Republic of Yemen</option>
    <option value="PE" label="Peru">Peru</option>
    <option value="PH" label="Philippines">Philippines</option>
    <option value="PN" label="Pitcairn Islands">Pitcairn Islands</option>
    <option value="PL" label="Poland">Poland</option>
    <option value="PT" label="Portugal">Portugal</option>
    <option value="PR" label="Puerto Rico">Puerto Rico</option>
    <option value="QA" label="Qatar">Qatar</option>
    <option value="RO" label="Romania">Romania</option>
    <option value="RU" label="Russia">Russia</option>
    <option value="RW" label="Rwanda">Rwanda</option>
    <option value="RE" label="Réunion">Réunion</option>
    <option value="BL" label="Saint Barthélemy">Saint Barthélemy</option>
    <option value="SH" label="Saint Helena">Saint Helena</option>
    <option value="KN" label="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
    <option value="LC" label="Saint Lucia">Saint Lucia</option>
    <option value="MF" label="Saint Martin">Saint Martin</option>
    <option value="PM" label="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
    <option value="VC" label="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
    <option value="WS" label="Samoa">Samoa</option>
    <option value="SM" label="San Marino">San Marino</option>
    <option value="SA" label="Saudi Arabia">Saudi Arabia</option>
    <option value="SN" label="Senegal">Senegal</option>
    <option value="RS" label="Serbia">Serbia</option>
    <option value="CS" label="Serbia and Montenegro">Serbia and Montenegro</option>
    <option value="SC" label="Seychelles">Seychelles</option>
    <option value="SL" label="Sierra Leone">Sierra Leone</option>
    <option value="SG" label="Singapore">Singapore</option>
    <option value="SK" label="Slovakia">Slovakia</option>
    <option value="SI" label="Slovenia">Slovenia</option>
    <option value="SB" label="Solomon Islands">Solomon Islands</option>
    <option value="SO" label="Somalia">Somalia</option>
    <option value="ZA" label="South Africa">South Africa</option>
    <option value="GS" label="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
    <option value="KR" label="South Korea">South Korea</option>
    <option value="ES" label="Spain">Spain</option>
    <option value="LK" label="Sri Lanka">Sri Lanka</option>
    <option value="SD" label="Sudan">Sudan</option>
    <option value="SR" label="Suriname">Suriname</option>
    <option value="SJ" label="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
    <option value="SZ" label="Swaziland">Swaziland</option>
    <option value="SE" label="Sweden">Sweden</option>
    <option value="CH" label="Switzerland">Switzerland</option>
    <option value="SY" label="Syria">Syria</option>
    <option value="ST" label="São Tomé and Príncipe">São Tomé and Príncipe</option>
    <option value="TW" label="Taiwan">Taiwan</option>
    <option value="TJ" label="Tajikistan">Tajikistan</option>
    <option value="TZ" label="Tanzania">Tanzania</option>
    <option value="TH" label="Thailand">Thailand</option>
    <option value="TL" label="Timor-Leste">Timor-Leste</option>
    <option value="TG" label="Togo">Togo</option>
    <option value="TK" label="Tokelau">Tokelau</option>
    <option value="TO" label="Tonga">Tonga</option>
    <option value="TT" label="Trinidad and Tobago">Trinidad and Tobago</option>
    <option value="TN" label="Tunisia">Tunisia</option>
    <option value="TR" label="Turkey">Turkey</option>
    <option value="TM" label="Turkmenistan">Turkmenistan</option>
    <option value="TC" label="Turks and Caicos Islands">Turks and Caicos Islands</option>
    <option value="TV" label="Tuvalu">Tuvalu</option>
    <option value="UM" label="U.S. Minor Outlying Islands">U.S. Minor Outlying Islands</option>
    <option value="PU" label="U.S. Miscellaneous Pacific Islands">U.S. Miscellaneous Pacific Islands</option>
    <option value="VI" label="U.S. Virgin Islands">U.S. Virgin Islands</option>
    <option value="UG" label="Uganda">Uganda</option>
    <option value="UA" label="Ukraine">Ukraine</option>
    <option value="SU" label="Union of Soviet Socialist Republics">Union of Soviet Socialist Republics</option>
    <option value="AE" label="United Arab Emirates">United Arab Emirates</option>
    <option value="GB" label="United Kingdom">United Kingdom</option>
    <option value="US" label="United States" selected="selected">United States</option>
    <option value="ZZ" label="Unknown or Invalid Region">Unknown or Invalid Region</option>
    <option value="UY" label="Uruguay">Uruguay</option>
    <option value="UZ" label="Uzbekistan">Uzbekistan</option>
    <option value="VU" label="Vanuatu">Vanuatu</option>
    <option value="VA" label="Vatican City">Vatican City</option>
    <option value="VE" label="Venezuela">Venezuela</option>
    <option value="VN" label="Vietnam">Vietnam</option>
    <option value="WK" label="Wake Island">Wake Island</option>
    <option value="WF" label="Wallis and Futuna">Wallis and Futuna</option>
    <option value="EH" label="Western Sahara">Western Sahara</option>
    <option value="YE" label="Yemen">Yemen</option>
    <option value="ZM" label="Zambia">Zambia</option>
    <option value="ZW" label="Zimbabwe">Zimbabwe</option>
    <option value="AX" label="Åland Islands">Åland Islands</option>
</select>';
if ( $selected != '')
    $html =  str_replace('selected="selected"', '', $html);
    $html =  str_replace('<option value="'.$selected.'" ', '<option value="'.$selected.'" selected="selected" ', $html);

return $html;
}
/**
*
*/
function newswire_select_schema( $selected = '') {
    $html = '<select name="newswire_data[schema_id]" id="schema_id">
    <option value="0" label="-- Select --">-- Select --</option>
    <option value="1" label="EducationalOrganization">EducationalOrganization</option>
    <option value="2" label="---|CollegeOrUniversity">---|CollegeOrUniversity</option>
    <option value="4" label="---|ElementarySchool">---|ElementarySchool</option>
    <option value="5" label="---|HighSchool">---|HighSchool</option>
    <option value="6" label="---|MiddleSchool">---|MiddleSchool</option>
    <option value="7" label="---|Preschool">---|Preschool</option>
    <option value="8" label="---|School">---|School</option>
    <option value="9" label="GovernmentOrganization">GovernmentOrganization</option>
    <option value="14" label="LocalBusiness" selected="selected">LocalBusiness</option>
    <option value="15" label="---|AnimalShelter">---|AnimalShelter</option>
    <option value="16" label="---|AutomotiveBusiness">---|AutomotiveBusiness</option>
    <option value="17" label="-------|AutoBodyShop">-------|AutoBodyShop</option>
    <option value="18" label="-------|AutoDealer">-------|AutoDealer</option>
    <option value="19" label="-------|AutoPartsStore">-------|AutoPartsStore</option>
    <option value="20" label="-------|AutoRental">-------|AutoRental</option>
    <option value="21" label="-------|AutoRepair">-------|AutoRepair</option>
    <option value="22" label="-------|AutoWash">-------|AutoWash</option>
    <option value="23" label="-------|GasStation">-------|GasStation</option>
    <option value="24" label="-------|MotorcycleDealer">-------|MotorcycleDealer</option>
    <option value="25" label="-------|MotorcycleRepair">-------|MotorcycleRepair</option>
    <option value="26" label="---|ChildCare">---|ChildCare</option>
    <option value="27" label="---|DryCleaningOrLaundry">---|DryCleaningOrLaundry</option>
    <option value="28" label="---|EmergencyService">---|EmergencyService</option>
    <option value="29" label="-------|FireStation">-------|FireStation</option>
    <option value="30" label="-------|Hospital">-------|Hospital</option>
    <option value="31" label="-------|PoliceStation">-------|PoliceStation</option>
    <option value="32" label="---|EmploymentAgency">---|EmploymentAgency</option>
    <option value="33" label="---|EntertainmentBusiness">---|EntertainmentBusiness</option>
    <option value="34" label="-------|AdultEntertainment">-------|AdultEntertainment</option>
    <option value="35" label="-------|AmusementPark">-------|AmusementPark</option>
    <option value="36" label="-------|ArtGallery">-------|ArtGallery</option>
    <option value="37" label="-------|Casino">-------|Casino</option>
    <option value="38" label="-------|ComedyClub">-------|ComedyClub</option>
    <option value="39" label="-------|MovieTheater">-------|MovieTheater</option>
    <option value="40" label="-------|NightClub">-------|NightClub</option>
    <option value="41" label="---|FinancialService">---|FinancialService</option>
    <option value="42" label="-------|AccountingService">-------|AccountingService</option>
    <option value="43" label="-------|AutomatedTeller">-------|AutomatedTeller</option>
    <option value="44" label="-------|BankOrCreditUnion">-------|BankOrCreditUnion</option>
    <option value="45" label="-------|InsuranceAgency">-------|InsuranceAgency</option>
    <option value="46" label="---|FoodEstablishment">---|FoodEstablishment</option>
    <option value="47" label="-------|Bakery">-------|Bakery</option>
    <option value="48" label="-------|BarOrPub">-------|BarOrPub</option>
    <option value="49" label="-------|Brewery">-------|Brewery</option>
    <option value="50" label="-------|CafeOrCoffeeShop">-------|CafeOrCoffeeShop</option>
    <option value="51" label="-------|FastFoodRestaurant">-------|FastFoodRestaurant</option>
    <option value="52" label="-------|IceCreamShop">-------|IceCreamShop</option>
    <option value="53" label="-------|Restaurant">-------|Restaurant</option>
    <option value="54" label="-------|Winery">-------|Winery</option>
    <option value="55" label="---|GovernmentOffice">---|GovernmentOffice</option>
    <option value="56" label="-------|PostOffice">-------|PostOffice</option>
    <option value="57" label="---|HealthAndBeautyBusiness">---|HealthAndBeautyBusiness</option>
    <option value="58" label="-------|BeautySalon">-------|BeautySalon</option>
    <option value="59" label="-------|DaySpa">-------|DaySpa</option>
    <option value="60" label="-------|HairSalon">-------|HairSalon</option>
    <option value="61" label="-------|HealthClub">-------|HealthClub</option>
    <option value="62" label="-------|NailSalon">-------|NailSalon</option>
    <option value="63" label="-------|TattooParlor">-------|TattooParlor</option>
    <option value="64" label="---|HomeAndConstructionBusiness">---|HomeAndConstructionBusiness</option>
    <option value="65" label="-------|Electrician">-------|Electrician</option>
    <option value="66" label="-------|GeneralContractor">-------|GeneralContractor</option>
    <option value="67" label="-------|HVACBusiness">-------|HVACBusiness</option>
    <option value="68" label="-------|HousePainter">-------|HousePainter</option>
    <option value="69" label="-------|Locksmith">-------|Locksmith</option>
    <option value="70" label="-------|MovingCompany">-------|MovingCompany</option>
    <option value="71" label="-------|Plumber">-------|Plumber</option>
    <option value="72" label="-------|RoofingContractor">-------|RoofingContractor</option>
    <option value="73" label="---|InternetCafe">---|InternetCafe</option>
    <option value="74" label="---|Library">---|Library</option>
    <option value="75" label="---|LodgingBusiness">---|LodgingBusiness</option>
    <option value="76" label="-------|BedAndBreakfast">-------|BedAndBreakfast</option>
    <option value="77" label="-------|Hostel">-------|Hostel</option>
    <option value="78" label="-------|Hotel">-------|Hotel</option>
    <option value="79" label="-------|Motel">-------|Motel</option>
    <option value="80" label="---|MedicalOrganization">---|MedicalOrganization</option>
    <option value="81" label="-------|Dentist">-------|Dentist</option>
    <option value="82" label="-------|DiagnosticLab">-------|DiagnosticLab</option>
    <option value="83" label="-------|Hospital">-------|Hospital</option>
    <option value="84" label="-------|MedicalClinic">-------|MedicalClinic</option>
    <option value="85" label="-------|Optician">-------|Optician</option>
    <option value="86" label="-------|Pharmacy">-------|Pharmacy</option>
    <option value="87" label="-------|Pharmacy">-------|Pharmacy</option>
    <option value="88" label="-------|Physician">-------|Physician</option>
    <option value="89" label="-------|VeterinaryCare">-------|VeterinaryCare</option>
    <option value="90" label="---|ProfessionalService">---|ProfessionalService</option>
    <option value="91" label="-------|AccountingService">-------|AccountingService</option>
    <option value="92" label="-------|Attorney">-------|Attorney</option>
    <option value="93" label="-------|Dentist">-------|Dentist</option>
    <option value="94" label="-------|Electrician">-------|Electrician</option>
    <option value="95" label="-------|GeneralContractor">-------|GeneralContractor</option>
    <option value="96" label="-------|HousePainter">-------|HousePainter</option>
    <option value="97" label="-------|Locksmith">-------|Locksmith</option>
    <option value="98" label="-------|Notary">-------|Notary</option>
    <option value="99" label="-------|Plumber">-------|Plumber</option>
    <option value="100" label="-------|RoofingContractor">-------|RoofingContractor</option>
    <option value="101" label="-------|RoofingContractor">-------|RoofingContractor</option>
    <option value="102" label="---|RadioStation">---|RadioStation</option>
    <option value="103" label="---|RealEstateAgent">---|RealEstateAgent</option>
    <option value="104" label="---|RecyclingCenter">---|RecyclingCenter</option>
    <option value="105" label="---|RecyclingCenter">---|RecyclingCenter</option>
    <option value="106" label="---|SelfStorage">---|SelfStorage</option>
    <option value="107" label="---|ShoppingCenter">---|ShoppingCenter</option>
    <option value="108" label="---|SportsActivityLocation">---|SportsActivityLocation</option>
    <option value="109" label="---|SportsActivityLocation">---|SportsActivityLocation</option>
    <option value="110" label="-------|BowlingAlley">-------|BowlingAlley</option>
    <option value="111" label="-------|ExerciseGym">-------|ExerciseGym</option>
    <option value="112" label="-------|GolfCourse">-------|GolfCourse</option>
    <option value="113" label="-------|HealthClub">-------|HealthClub</option>
    <option value="114" label="-------|PublicSwimmingPool">-------|PublicSwimmingPool</option>
    <option value="115" label="-------|SkiResort">-------|SkiResort</option>
    <option value="116" label="-------|SportsClub">-------|SportsClub</option>
    <option value="117" label="-------|StadiumOrArena">-------|StadiumOrArena</option>
    <option value="118" label="-------|TennisComplex">-------|TennisComplex</option>
    <option value="119" label="---|Store">---|Store</option>
    <option value="120" label="-------|AutoPartsStore">-------|AutoPartsStore</option>
    <option value="121" label="-------|BikeStore">-------|BikeStore</option>
    <option value="122" label="-------|BookStore">-------|BookStore</option>
    <option value="123" label="-------|ClothingStore">-------|ClothingStore</option>
    <option value="124" label="-------|ComputerStore">-------|ComputerStore</option>
    <option value="125" label="-------|ConvenienceStore">-------|ConvenienceStore</option>
    <option value="126" label="-------|DepartmentStore">-------|DepartmentStore</option>
    <option value="127" label="-------|ElectronicsStore">-------|ElectronicsStore</option>
    <option value="128" label="-------|Florist">-------|Florist</option>
    <option value="129" label="-------|FurnitureStore">-------|FurnitureStore</option>
    <option value="130" label="-------|GardenStore">-------|GardenStore</option>
    <option value="131" label="-------|GardenStore">-------|GardenStore</option>
    <option value="132" label="-------|GroceryStore">-------|GroceryStore</option>
    <option value="133" label="-------|HardwareStore">-------|HardwareStore</option>
    <option value="134" label="-------|HobbyShop">-------|HobbyShop</option>
    <option value="135" label="-------|HomeGoodsStore">-------|HomeGoodsStore</option>
    <option value="136" label="-------|JewelryStore">-------|JewelryStore</option>
    <option value="137" label="-------|LiquorStore">-------|LiquorStore</option>
    <option value="138" label="-------|MensClothingStore">-------|MensClothingStore</option>
    <option value="139" label="-------|MobilePhoneStore">-------|MobilePhoneStore</option>
    <option value="140" label="-------|MovieRentalStore">-------|MovieRentalStore</option>
    <option value="141" label="-------|MusicStore">-------|MusicStore</option>
    <option value="142" label="-------|OfficeEquipmentStore">-------|OfficeEquipmentStore</option>
    <option value="143" label="-------|OutletStore">-------|OutletStore</option>
    <option value="144" label="-------|PawnShop">-------|PawnShop</option>
    <option value="145" label="-------|PetStore">-------|PetStore</option>
    <option value="146" label="-------|ShoeStore">-------|ShoeStore</option>
    <option value="147" label="-------|SportingGoodsStore">-------|SportingGoodsStore</option>
    <option value="148" label="-------|TireShop">-------|TireShop</option>
    <option value="149" label="-------|ToyStore">-------|ToyStore</option>
    <option value="150" label="-------|WholesaleStore">-------|WholesaleStore</option>
    <option value="151" label="---|TelevisionStation">---|TelevisionStation</option>
    <option value="152" label="---|TouristInformationCenter">---|TouristInformationCenter</option>
    <option value="153" label="---|TravelAgency">---|TravelAgency</option>
</select>';
//if ( empty($selected) ) $selected = 14;
$html =  str_replace('<option value="'.$selected.'" ', '<option value="'.$selected.'" selected="selected" ', $html);
return $html;

}


/**
* This will be replace by db soon coming from syncing newswire.net website
*/
function newswire_select_categories($selected = '') 
{
    $html = '<select name="newswire_data[category_id2]" id="category_id2">
    <option value="" label=""></option>
    <option value="370" label="World">World</option>
    <option value="376" label="---|Africa">---|Africa</option>
    <option value="377" label="---|Americas">---|Americas</option>
    <option value="378" label="---|Asia">---|Asia</option>
    <option value="379" label="---|Europe">---|Europe</option>
    <option value="380" label="---|Middle East">---|Middle East</option>
    <option value="371" label="US">US</option>
    <option value="25" label="---|Politics">---|Politics</option>
    <option value="23" label="---|Sports">---|Sports</option>
    <option value="387" label="------|College Sports">------|College Sports</option>
    <option value="386" label="------|Pro Sports">------|Pro Sports</option>
    <option value="375" label="---|Opinion">---|Opinion</option>
    <option value="98" label="---|Education">---|Education</option>
    <option value="156" label="Local">Local</option>
    <option value="261" label="---|Alabama">---|Alabama</option>
    <option value="401" label="------|Darling">------|Darling</option>
    <option value="400" label="------|Homewood">------|Homewood</option>
    <option value="262" label="---|Alaska">---|Alaska</option>
    <option value="354" label="------|Anchorage">------|Anchorage</option>
    <option value="263" label="---|Arizona">---|Arizona</option>
    <option value="363" label="------|Phoenix">------|Phoenix</option>
    <option value="264" label="---|Arkansas">---|Arkansas</option>
    <option value="265" label="---|California">---|California</option>
    <option value="338" label="------|Los Angeles">------|Los Angeles</option>
    <option value="339" label="------|San Diego">------|San Diego</option>
    <option value="266" label="---|Colorado">---|Colorado</option>
    <option value="337" label="------|Denver">------|Denver</option>
    <option value="267" label="---|Connecticut">---|Connecticut</option>
    <option value="268" label="---|Delaware">---|Delaware</option>
    <option value="270" label="---|Florida">---|Florida</option>
    <option value="356" label="------|Lakeland">------|Lakeland</option>
    <option value="350" label="------|New Port Richey">------|New Port Richey</option>
    <option value="355" label="------|Orlando">------|Orlando</option>
    <option value="351" label="------|Spring Hill">------|Spring Hill</option>
    <option value="271" label="------|Tampa">------|Tampa</option>
    <option value="272" label="---|Georgia">---|Georgia</option>
    <option value="273" label="---|Hawaii">---|Hawaii</option>
    <option value="274" label="---|Idaho">---|Idaho</option>
    <option value="275" label="------|Boise">------|Boise</option>
    <option value="276" label="---|Illinois">---|Illinois</option>
    <option value="277" label="---|Indiana">---|Indiana</option>
    <option value="278" label="---|Iowa">---|Iowa</option>
    <option value="279" label="---|Kansas">---|Kansas</option>
    <option value="280" label="---|Kentucky">---|Kentucky</option>
    <option value="281" label="---|Louisiana">---|Louisiana</option>
    <option value="282" label="---|Maine">---|Maine</option>
    <option value="283" label="---|Maryland">---|Maryland</option>
    <option value="284" label="---|Massachusetts">---|Massachusetts</option>
    <option value="333" label="------|Boston">------|Boston</option>
    <option value="285" label="---|Michigan">---|Michigan</option>
    <option value="286" label="---|Minnesota">---|Minnesota</option>
    <option value="287" label="---|Mississippi">---|Mississippi</option>
    <option value="288" label="---|Missouri">---|Missouri</option>
    <option value="341" label="------|St Louis">------|St Louis</option>
    <option value="289" label="---|Montana">---|Montana</option>
    <option value="290" label="---|Nebraska">---|Nebraska</option>
    <option value="291" label="---|Nevada">---|Nevada</option>
    <option value="336" label="------|Las Vegas">------|Las Vegas</option>
    <option value="292" label="---|New Hampshire">---|New Hampshire</option>
    <option value="293" label="---|New Jersey">---|New Jersey</option>
    <option value="294" label="---|New Mexico">---|New Mexico</option>
    <option value="295" label="---|New York">---|New York</option>
    <option value="340" label="------|New York City">------|New York City</option>
    <option value="296" label="---|North Carolina">---|North Carolina</option>
    <option value="345" label="------|Charlotte">------|Charlotte</option>
    <option value="366" label="------|Raleigh">------|Raleigh</option>
    <option value="395" label="---|North Dakota">---|North Dakota</option>
    <option value="297" label="---|Ohio">---|Ohio</option>
    <option value="346" label="------|Akron">------|Akron</option>
    <option value="349" label="------|Cincinnatti">------|Cincinnatti</option>
    <option value="347" label="------|Cleveland">------|Cleveland</option>
    <option value="298" label="------|Columbus">------|Columbus</option>
    <option value="299" label="------|Hilliard">------|Hilliard</option>
    <option value="348" label="------|Youngstown">------|Youngstown</option>
    <option value="300" label="---|Oklahoma">---|Oklahoma</option>
    <option value="301" label="---|Oregon">---|Oregon</option>
    <option value="369" label="------|Portland">------|Portland</option>
    <option value="302" label="---|Pennsylvania">---|Pennsylvania</option>
    <option value="344" label="------|Philadelphia">------|Philadelphia</option>
    <option value="303" label="---|PUERTO RICO">---|PUERTO RICO</option>
    <option value="304" label="---|Rhode Island">---|Rhode Island</option>
    <option value="305" label="---|South Carolina">---|South Carolina</option>
    <option value="306" label="---|South Dakota">---|South Dakota</option>
    <option value="307" label="---|Tennessee">---|Tennessee</option>
    <option value="308" label="---|Texas">---|Texas</option>
    <option value="320" label="------|Austin">------|Austin</option>
    <option value="359" label="------|Dallas">------|Dallas</option>
    <option value="360" label="------|Fort Worth">------|Fort Worth</option>
    <option value="364" label="------|San Antonio">------|San Antonio</option>
    <option value="309" label="---|Utah">---|Utah</option>
    <option value="310" label="------|Holladay">------|Holladay</option>
    <option value="311" label="------|Salt Lake City">------|Salt Lake City</option>
    <option value="313" label="------|South Jordan">------|South Jordan</option>
    <option value="312" label="------|West Jordan">------|West Jordan</option>
    <option value="314" label="---|Vermont">---|Vermont</option>
    <option value="315" label="---|Virginia">---|Virginia</option>
    <option value="316" label="---|Washington">---|Washington</option>
    <option value="367" label="------|Seattle">------|Seattle</option>
    <option value="342" label="------|Spokane">------|Spokane</option>
    <option value="343" label="------|Spokane Valley">------|Spokane Valley</option>
    <option value="317" label="---|West Virginia">---|West Virginia</option>
    <option value="318" label="---|Wisconsin">---|Wisconsin</option>
    <option value="361" label="------|Milwaukee">------|Milwaukee</option>
    <option value="319" label="---|Wyoming">---|Wyoming</option>
    <option value="269" label="---|District Of Columbia">---|District Of Columbia</option>
    <option value="396" label="---|Alberta">---|Alberta</option>
    <option value="397" label="---|British Columbia">---|British Columbia</option>
    <option value="398" label="---|Manitoba">---|Manitoba</option>
    <option value="399" label="---|New Brunswick">---|New Brunswick</option>
    <option value="322" label="---|Ontario">---|Ontario</option>
    <option value="323" label="------|Kingston">------|Kingston</option>
    <option value="368" label="------|Toronto">------|Toronto</option>
    <option value="352" label="---|Quebec">---|Quebec</option>
    <option value="353" label="------|Montreal">------|Montreal</option>
    <option value="324" label="---|United Kingdom">---|United Kingdom</option>
    <option value="325" label="------|London">------|London</option>
    <option value="326" label="---|Australia">---|Australia</option>
    <option value="334" label="------|Adelaide">------|Adelaide</option>
    <option value="331" label="------|Brisbane">------|Brisbane</option>
    <option value="329" label="------|Gold Coast">------|Gold Coast</option>
    <option value="330" label="------|Melbourne">------|Melbourne</option>
    <option value="335" label="------|Newcastle">------|Newcastle</option>
    <option value="332" label="------|Perth">------|Perth</option>
    <option value="328" label="------|Sydney">------|Sydney</option>
    <option value="66" label="Business">Business</option>
    <option value="381" label="---|Global">---|Global</option>
    <option value="97" label="---|Economy">---|Economy</option>
    <option value="382" label="---|Energy">---|Energy</option>
    <option value="217" label="---|Real Estate">---|Real Estate</option>
    <option value="96" label="---|eCommerce">---|eCommerce</option>
    <option value="388" label="---|Mobile">---|Mobile</option>
    <option value="390" label="---|SEO">---|SEO</option>
    <option value="389" label="---|Social Media">---|Social Media</option>
    <option value="391" label="---|Trends">---|Trends</option>
    <option value="392" label="---|Your Money">---|Your Money</option>
    <option value="242" label="Technology">Technology</option>
    <option value="372" label="---|Science">---|Science</option>
    <option value="109" label="---|Environment">---|Environment</option>
    <option value="383" label="---|Space and Cosmos">---|Space and Cosmos</option>
    <option value="131" label="Health">Health</option>
    <option value="403" label="---|Cancer">---|Cancer</option>
    <option value="384" label="---|Exercise &amp; Fitness">---|Exercise &amp; Fitness</option>
    <option value="385" label="---|Weightloss">---|Weightloss</option>
    <option value="373" label="Travel">Travel</option>
    <option value="404" label="---|Entertainment">---|Entertainment</option>
    <option value="113" label="Finance">Finance</option>
    <option value="163" label="---|Markets">---|Markets</option>
    <option value="393" label="---|Business Education">---|Business Education</option>
    <option value="402" label="---|Cryptocurrency">---|Cryptocurrency</option>
    <option value="394" label="---|Entrepreneurship">---|Entrepreneurship</option>
</select>';

//add selected
$html =  str_replace('<option value="'.$selected.'" ', '<option value="'.$selected.'" selected="selected" ', $html);
return $html;   

}