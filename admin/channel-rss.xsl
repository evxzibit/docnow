<?xml version="1.0" ?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" />
<xsl:template match="/">
	<html>
	<head>
		<title><xsl:value-of select="/rss/channel/title" /></title>
	</head>
	<body>
		<xsl:attribute name="style">font-family: Verdana; font-size: 10pt; background-color: #ffffff</xsl:attribute>
		<xsl:apply-templates />
	</body>
	</html>
</xsl:template>

<xsl:template match="/rss/channel">

	<!-- Back to top link -->
	<a><xsl:attribute name="name">top</xsl:attribute></a>

	<p>
	<!-- Display the image if it was specified for this channel -->
	<xsl:if test="./image">
	<a><xsl:attribute name="href"><xsl:value-of select="./image/link" /></xsl:attribute>
	<img>
	<xsl:attribute name="border">0</xsl:attribute>
	<xsl:attribute name="src"><xsl:value-of select="./image/url" /></xsl:attribute>
	<xsl:attribute name="alt"><xsl:value-of select="./image/description" /></xsl:attribute>
	<xsl:attribute name="title"><xsl:value-of select="./image/title" /></xsl:attribute>
	</img>
	</a><br />
	</xsl:if>

	<big><big><xsl:value-of select="./title" /></big></big><br />
	Last updated on: <xsl:value-of select="./lastBuildDate" /><br />
	<xsl:choose>
		<xsl:when test="count(./item) = 1">
			There is 1 item listed in this channel.
		</xsl:when>
		<xsl:otherwise>
			There are <xsl:value-of select="count(./item)" /> items listed in this channel. The &amp;$MaxItems= parameter can be used to request additional results.
		</xsl:otherwise>
	</xsl:choose>
	</p><hr />

	<p align="center">
	<xsl:attribute name="style">color: #909090</xsl:attribute>
	<xsl:attribute name="alignment">center</xsl:attribute>
	This message and the rest of the page's formatting is supplied by the sample style sheet <B>channel-rss.xsl</B>. To access the channel's content in its original XML format, view the page's source.
	</p><hr />

	<table>
	<xsl:attribute name="border">0</xsl:attribute>
	<xsl:attribute name="cellpadding">10</xsl:attribute>
	<xsl:attribute name="cellspacing">10</xsl:attribute>
	<xsl:attribute name="style">font-size: 10pt</xsl:attribute>
	<xsl:attribute name="width">100%</xsl:attribute>
	<xsl:for-each select="./item">
	<tr>
		<xsl:choose>
			<xsl:when test="position() mod 2 = 0">
				<xsl:attribute name="style">background: #ffffff; margin: 10 10 10 10</xsl:attribute>
			</xsl:when>
			<xsl:otherwise>
				<xsl:attribute name="style">background: #EEEEEE; margin: 10 10 10 10</xsl:attribute>
			</xsl:otherwise>
		</xsl:choose>
		<td>
		<xsl:attribute name="style">border: 1px solid #999999</xsl:attribute>
		<table>
		<xsl:attribute name="style">font-size: 8pt</xsl:attribute>
		<xsl:attribute name="border">0</xsl:attribute>
		<xsl:attribute name="cellspacing">0</xsl:attribute>
		<xsl:attribute name="cellpadding">0</xsl:attribute>
		<xsl:attribute name="width">100%</xsl:attribute>
		<tr>
			<td>
			<b><a><xsl:attribute name="href"><xsl:value-of select="./link" /></xsl:attribute><xsl:value-of select="./title" /></a></b><br />
			</td>
		</tr>
		<tr>
			<td>
			Published: <xsl:value-of select="./pubDate" />
			</td>
		</tr>
		</table><br />
		<div>
		<xsl:attribute name="style">font-size: 8pt</xsl:attribute>
		<xsl:value-of disable-output-escaping="yes" select="./description" />
		</div>
		</td>
	</tr>
	</xsl:for-each>
	</table><hr />

	<p>Copyright (C), Envent Holdings (Pty) Ltd t/a ENSIGHT. Visit <a href="http://support.ensight.co.uk">http://support.ensight.co.uk</a> for product support.</p>

</xsl:template>
</xsl:stylesheet>
