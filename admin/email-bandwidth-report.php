<?
set_time_limit (0);

if (!defined ("DB_INCLUDED"))                   { include_once ("../modules/DB.php"); }
if (!defined ("CONNECT_INCLUDED"))              { include_once ("../modules/connect.php"); }
if (!defined ("UTILS_INCLUDED"))                { include_once ("../modules/utils.php"); }
if (!defined ("FORM_INCLUDED"))                 { include_once ("../modules/form.php"); }
if (!defined ("SESSION_INCLUDED"))              { include_once ("../modules/session.php"); }
if (!defined ("PROFILE_INCLUDED"))              { include_once ("../modules/profile.php"); }
if (!defined ("MIME_INCLUDED"))                 { include_once ("../modules/MIME.php"); }
if (!defined ("DATE_INCLUDED"))                 { include_once ("../modules/date.php"); }
if (!defined ("FUSION_INCLUDED"))               { include_once ("../modules/fusion.php"); }

function RetrieveMessage ($Mail_ID) {
//--- Returns a message stored in the DB

        $SQL = "SELECT Campaign_ID, From_STRING, FromEmail_STRING, CCTo_STRING, ReplyTo_STRING, MailSubject_STRING, MailText_STRING, MailHTML_STRING, OfflineImages_NUM, Priority_NUM, AttachedFile1_STRING, AttachedFile2_STRING, AttachedFile3_STRING, AttachedType1_STRING, AttachedType2_STRING, AttachedType3_STRING, AttachedID1_STRING, AttachedID2_STRING, AttachedID3_STRING, SendStarted_DATE, SendCompleted_DATE, SendVolume_NUM, OpenVolume_NUM, BouncedVolume_NUM FROM Mails WHERE Mail_ID = '$Mail_ID'";
        $Query = QueryDB ($SQL);

        return ReadFromDB ($Query);

}

function GetMessageStats ($Mail_ID) {
//--- Calculates the average message size

        $Message = RetrieveMessage ($Mail_ID);

        if ($Message) {

                $NameFrom = $Message['From_STRING'];
                $EmlFrom = $Message['FromEmail_STRING'];
                $CCTo = $Message['CCTo_STRING'];
                $ReplyTo = $Message['ReplyTo_STRING'];
                $Subject = $Message['MailSubject_STRING'];
                $TextMessage = $Message['MailText_STRING'];
                $HTMLMessage = $Message['MailHTML_STRING'];
                $OfflineImages = $Message['OfflineImages_NUM'];
                $Priority = $Message['Priority_NUM'];
                $File1 = $Message['AttachedFile1_STRING'];
                $File2 = $Message['AttachedFile2_STRING'];
                $File3 = $Message['AttachedFile3_STRING'];
                $File1_type = $Message['AttachedType1_STRING'];
                $File2_type = $Message['AttachedType2_STRING'];
                $File3_type = $Message['AttachedType3_STRING'];
                $ID1 = $Message['AttachedID1_STRING'];
                $ID2 = $Message['AttachedID2_STRING'];
                $ID3 = $Message['AttachedID3_STRING'];

        }

        if (!$ID1) {
                $ID1 = None;
        }
        if (!$ID2) {
                $ID2 = None;
        }
        if (!$ID3) {
                $ID3 = None;
        }

        $UploadDir = CONTENT_FILES;

        if (($File1) && ($File1 != 'none')) {
                AttachMultipartMIMEFile ($UploadDir."/".$File1, $File1, $File1_type, $File1, $ID1);
        }
        if (($File2) && ($File2 != 'none')) {
                AttachMultipartMIMEFile ($UploadDir."/".$File2, $File2, $File2_type, $File1, $ID2);
        }
        if (($File3) && ($File3 != 'none')) {
                AttachMultipartMIMEFile ($UploadDir."/".$File3, $File3, $File3_type, $File1, $ID3);
        }

        if ($TextMessage != '') {
                $PTextMessage = (MailHeader != '' ? MailHeader."\n" : '').@ProcessFusion (stripslashes ($TextMessage), array ("Item_ID" => 0, "Reference_ID" => 0, "Origin" => urlencode ("Mail ".$Mail_ID), "IncludePath" => true, "ContentType" => "text/plain"), $Profile_ID, "FusionText").(MailFooter != '' ? "\n".MailFooter : '');
        } else {
                $PTextMessage = '';
        }
        if ($HTMLMessage != '') {
                $PHTMLMessage = (HTMLMailHeader != '' ? HTMLMailHeader."\n" : '').@ProcessFusion (stripslashes ($HTMLMessage), array ("Item_ID" => 0, "Reference_ID" => 0, "Origin" => urlencode ("Mail ".$Mail_ID), "IncludePath" => true, "ContentType" => "text/html", "OfflineImages" => $OfflineImages), USER_NOBODY, DefaultFusionProcessor).(HTMLMailFooter != '' ? "\n".HTMLMailFooter : '');
        } else {
                $PHTMLMessage = '';
        }

        $Size = strlen (CompileMail ("test@ensight.co.za", "Test Subject", RetrieveMultipartMIMEBody ($PTextMessage, $PHTMLMessage), RetrieveMultipartMIMEHeader ("test@ensight.co.za", None, "test@ensight.co.za", PRIORITY_NORMAL)));

        if (!$OfflineImages) {
                //--- Do it again, but with images embedded
                $PHTMLMessage = (HTMLMailHeader != '' ? HTMLMailHeader."\n" : '').ProcessFusion (stripslashes ($HTMLMessage), array ("Item_ID" => 0, "Reference_ID" => 0, "Origin" => urlencode ("Mail ".$Mail_ID), "IncludePath" => true, "ContentType" => "text/html", "OfflineImages" => true), $Profile_ID, $Processor).(HTMLMailFooter != '' ? "\n".HTMLMailFooter : '') - $Size;
                $ExcessSize = strlen (CompileMail ("test@ensight.co.za", "Test Subject", RetrieveMultipartMIMEBody ($PTextMessage, $PHTMLMessage), RetrieveMultipartMIMEHeader ("test@ensight.co.za", None, "test@ensight.co.za", PRIORITY_NORMAL)));
                if ($ExcessSize < 0) {
                        $ExcessSize = 0;
                }
        }

        DestroyFusionCache ();

        return array ("Subject" => $Subject, "Sent" => $Message['SendStarted_DATE'], "Size" => $Size, "ExcessSize" => $ExcessSize);

}

$SQL = "SELECT Mail_ID, SendStarted_DATE FROM Mails WHERE SendStarted_DATE >= '$StartDate 00:00:00' AND SendStarted_DATE <= '$EndDate 23:59:59' ORDER BY Mail_ID";
$Query = QueryDB ($SQL);

$Count = 0;
$CSize = 0;
$ESize = 0;
$ExcessTotal = 0;
$Total = 0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<HTML>
<HEAD>
        <TITLE>E-mail Bandwidth Usage Report</TITLE>
        <STYLE>
        BODY, TD {
                font-family: Trebuchet MS; font-size: 11px
        }
        </STYLE>
</HEAD>

<BODY>

<TABLE border=0 cellpadding=2 cellspacing=0 width="100%" style="border: 1px solid navy">
<TR>
        <TD colspan=8 style="border: 1px solid navy">
        <BIG><B>Ensight E-mail Bandwidth Usage Report for <? echo FriendlyName; ?> - <? echo $StartDate; ?> to <? echo $EndDate; ?></B></BIG>
        </TD>
</TR>
<TR>
        <TD style="border: 1px solid navy" align="center"><B>Mail #</B></TD>
        <TD style="border: 1px solid navy" align="center"><B>Subject</B></TD>
        <TD style="border: 1px solid navy" align="center"><B>Date Started</B></TD>
        <TD style="border: 1px solid navy" align="center"><B>No. of Mails</B></TD>
        <TD style="border: 1px solid navy" align="center"><B>No. Opened</B></TD>
        <TD style="border: 1px solid navy" align="center"><B>Size per Mail (in bytes)</B></TD>
        <TD style="border: 1px solid navy" align="center"><B>Sub-Total</B></TD>
        <TD style="border: 1px solid navy" align="center"><B>Estimated Total Usage <a href="#" onclick="alert ('The value indicated is the total estimated outbound bandwidth utilised per send, calculated by multiplying the number of opened messages with the excess message size brought about by linked (but not embedded) images. The excess is then added to the sub-total.'); return false">?</a></B></TD>
</TR>
<?
while ($Result = ReadFromDB ($Query)) {
        $SQL = "SELECT COUNT(*) AS counter FROM Mails_Sent WHERE Mail_ID = '".$Result['Mail_ID']."'";
        $SentQuery = QueryDB ($SQL);
        $SentCount = ReadFromDB ($SentQuery, DB_QUERY_FREE);
        $SQL = "SELECT COUNT(*) AS counter FROM Mails_Open WHERE Mail_ID = '".$Result['Mail_ID']."'";
        $OpenQuery = QueryDB ($SQL);
        $OpenCount = ReadFromDB ($OpenQuery, DB_QUERY_FREE);
        $Stats = GetMessageStats ($Result['Mail_ID']);
        $Count = $Count + ($SentCount['counter']);
        $CSize = $CSize + ($Stats['Size']);
        $ESize = $ESize + ($Stats['ExcessSize']);
        $ExcessTotal = $ExcessTotal + (($SentCount['counter'] * $Stats['Size']) + ($OpenCount['counter'] * $Stats['ExcessSize']));
        $Total = $Total + ($SentCount['counter'] * $Stats['Size']);
        unset ($AttachmentName);
        unset ($AttachmentType);
        unset ($AttachmentDesc);
        unset ($AttachmentXURL);
        unset ($AttachmentXCID);
?>
<TR>
        <TD style="border: 1px solid navy"><? echo $Result['Mail_ID']; ?></TD>
        <TD style="border: 1px solid navy"><? echo stripslashes ($Stats['Subject']); ?></TD>
        <TD style="border: 1px solid navy"><? echo date ("Y-m-d", DateTimeStamp (ConvertDateFromDB ($Stats['Sent']))); ?></TD>
        <TD style="border: 1px solid navy" align="right"><? echo number_format ($SentCount['counter'], 0); ?></TD>
        <TD style="border: 1px solid navy" align="right"><? echo number_format ($OpenCount['counter'], 0); ?></TD>
        <TD style="border: 1px solid navy" align="right"><? echo number_format ($Stats['Size'], 0); ?></TD>
        <TD style="border: 1px solid navy" align="right"><? echo number_format ((($SentCount['counter'] * $Stats['Size'])) / 1000 / 1000, 2); ?>Mb</TD>
        <TD style="border: 1px solid navy" align="right"><? echo number_format ((($SentCount['counter'] * $Stats['Size']) + ($OpenCount['counter'] * $Stats['ExcessSize'])) / 1000 / 1000, 2); ?>Mb</TD>
</TR>
<?
} // end while
?>
<TR>
        <TD style="border: 1px solid navy" align="right" colspan=3><B>Totals:</B></TD>
        <TD style="border: 1px solid navy" align="right"><B><? echo number_format ($Count, 0); ?></B></TD>
        <TD style="border: 1px solid navy" align="right"><B>-</B></TD>
        <TD style="border: 1px solid navy" align="right"><B><? echo number_format ($CSize, 0); ?></B></TD>
        <TD style="border: 1px solid navy" align="right"><B><? echo number_format ($Total / 1024 / 1024, 2); ?>Mb</B></TD>
        <TD style="border: 1px solid navy" align="right"><B><? echo number_format ($ExcessTotal / 1024 / 1024, 2); ?>Mb</B></TD>
</TR>
</TABLE>

</BODY>
</HTML>
