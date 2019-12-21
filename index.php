<?php
    function listFolderFiles($dir,$level=0){
        $arrIcon = array(
            'html' => 'fab fa-html5',
            'css' => 'fab fa-css3',
            'js' => 'fab fa-js',
            'php' => 'fab fa-php',
            'zip' => 'fas fa-file-archive',
            'jpg' => 'fas fa-image',
            'png' => 'fas fa-image',
            'gif' => 'fas fa-image',
            'ico' => 'fas fa-image',
            'ttf' => 'fas fa-font',
        );
        $ffs = scandir($dir);
        unset($ffs[array_search("index.php", $ffs)],$ffs[array_search("_assets", $ffs)],$ffs[array_search(".htaccess", $ffs)]);

        unset($ffs[array_search('.', $ffs, true)]);
        unset($ffs[array_search('..', $ffs, true)]);

        // prevent empty ordered elements
        if (count($ffs) < 1)
            return;

        $display = "";
        if ($level > 1) $display = "style='display: none'";
        echo '<div class="level-'.$level++.'"'.$display.'>';
        foreach($ffs as $ff){
            $onClick = "";
            if (is_dir($dir.'/'.$ff)) {
                $onClick = ' onClick="openDir(this)"';
                $icon = 'fas fa-folder';
            }else {
                $format = explode(".",$ff);
                if (array_key_exists(end($format), $arrIcon)) $icon = $arrIcon[end($format)];
                else $icon = 'fas fa-file-alt';
            }
            echo '<div class="level-'.$level.'"><div class="action-button"><i class="'.$icon.' border-right"'.$onClick.'></i><i class="fas fa-eye" title="View Raw" onClick="viewRaw(\''.str_replace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', $dir).'/'.$ff.'\')"></i></div> '.$ff;
            if(is_dir($dir.'/'.$ff)) listFolderFiles($dir.'/'.$ff,($level+1));
            echo '</div>';
        }
        echo '</div>';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Repository</title>
    <link rel=stylesheet href="_assets/style.css">
    <link rel=stylesheet href="_assets/fontawesome.css">
</head>
<body>
    <div class="container">
        <?php listFolderFiles($_SERVER['DOCUMENT_ROOT']); ?>
    </div>

    <script type="text/javascript">
        function openDir(target) {
            target.setAttribute('class', target.getAttribute('class').replace('folder','folder-open'))
            target.setAttribute('onClick', 'closeDir(this)')
            pClass = target.parentElement.parentElement.classList[0].split('-')
            subDirClass = pClass[0]+'-'+(parseInt(pClass[1])+1)
            subDir = target.parentElement.parentElement.querySelectorAll("[class='"+subDirClass+"']")

            subDir.forEach( function(element, index) {
                element.style.display = ''
            });
        }
        function closeDir(target) {
            target.setAttribute('class', target.getAttribute('class').replace('folder-open','folder'))
            target.setAttribute('onClick', 'openDir(this)')
            pClass = target.parentElement.parentElement.classList[0].split('-')
            subDirClass = pClass[0]+'-'+(parseInt(pClass[1])+1)
            subDir = target.parentElement.parentElement.querySelectorAll("[class='"+subDirClass+"']")

            subDir.forEach( function(element, index) {
                element.style.display = 'none'
            });
        }
        function viewRaw(path) {
            window.open(path, '_blank')
        }
    </script>
</body>
</html>