<?php
jimport( 'joomla.filesystem.file' );

$filename  =  'MWebinar_Webinar_Session_Report' . '-' . date("Y-m-d_H:i:s").'.csv';

$contents = "\n".'"Date"';

foreach($this->results['pages'] as $p) {
    if ($p->type=='field') {
        foreach ($p->content->fields as $f) {
            $contents .=  ',"'.addslashes($f->title).'"';
        }
    }
    if ($p->type=='question' || $p->type=='rating') {
        $contents .=  ',"'.addslashes($p->content->question).'"';
    }
}

$contents .= "\n";

foreach($this->results['results'] as $i => $item) {
    $contents .= '"'.$item[0].'"';
    foreach($this->results['pages'] as $p) {
        if ($p->type=='field') {
            foreach ($p->content->fields as $f) {
                if (isset($item[$p->id])) $contents .=  ',"'.addslashes($item[$p->id][$f->name]).'"';
                else $contents .=  ',""';
            }
        }
        if ($p->type=='question' || $p->type=='rating') {
            if (isset($item[$p->id]))  $contents .=  ',"'.addslashes($item[$p->id]).'"';
            else echo ',""';
        }
    }
	$contents .= "\n";
}

JResponse::clearHeaders();
JResponse::setHeader("Pragma","public");
JResponse::setHeader('Cache-Control', 'no-cache, must-revalidate', true);
JResponse::setHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT', true);
JResponse::setHeader('Content-Type', 'text/csv', true);
JResponse::setHeader('Content-Description', 'File Transfer', true);
JResponse::setHeader('Content-Disposition', 'attachment; filename="'.$filename.'"', true);
JResponse::setHeader('Content-Transfer-Encoding', 'binary', true);
JResponse::sendHeaders();
echo $contents;
exit();



