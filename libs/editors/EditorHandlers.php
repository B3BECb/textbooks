<?php
/*
 * ����������� ������ ���������
 */
class EditorHandlers
{
    /*
     * ��������� ��������.
     */
    function GetEditor($lessonId)
    {
        $_SESSION['lesson'] = $lessonId;
        $path = "themes/theme_".$_SESSION['CurrentTheme']."/lesson_".$_SESSION['lesson']."/lesson".$_SESSION['lesson'].".html";
        include "editor.html";
    }

    /*
     * ��������� ����� ���������.
     */
    function Save($text)
    {
        $path = "themes/theme_".$_SESSION['CurrentTheme']."/lesson_".$_SESSION['lesson']."/lesson".$_SESSION['lesson'].".html";
        $file = fopen($path, w);
        fwrite($file,$text);
        fclose($file);
    }
}