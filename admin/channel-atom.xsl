<?xml version="1.0" ?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" />
<xsl:template match="/">
	<html>
	<head>
		<title><xsl:value-of select="./feed/title" /></title>
	</head>
	<body>
		<xsl:attribute name="style">font-family: Verdana; font-size: 10pt; background-color: #ffffff</xsl:attribute>
		<xsl:apply-templates />
	</body>
	</html>
</xsl:template>

<xsl:template match="feed">

	<!-- Back to top link -->
	<a><xsl:attribute name="name">top</xsl:attribute></a>

	<p>
	<big><big><xsl:value-of select="./title" /></big></big><br />
	Last updated on: <xsl:value-of select="./modified" /><br />
	<xsl:choose>
		<xsl:when test="count(./entry) = 1">
		There is 1 item listed in this channel.
		</xsl:when>
		<xsl:otherwise>
		There are <xsl:value-of select="count(./entry)" /> items listed in this channel. The &amp;$MaxItems= parameter can be used to request additional results.
		</xsl:otherwise>
	</xsl:choose>
	</p><hr />

	<p align="center">
	<xsl:attribute name="style">color: #909090</xsl:attribute>
	<xsl:attribute name="alignment">center</xsl:attribute>
	This message and the rest of the page's formatting is supplied by the sample style sheet <B>channel-atom.xsl</B>. To access the channel's content in its original XML format, view the page's source.
	</p><hr />

	<table>
	<xsl:attribute name="border">0</xsl:attribute>
	<xsl:attribute name="cellpadding">10</xsl:attribute>
	<xsl:attribute name="cellspacing">10</xsl:attribute>
	<xsl:attribute name="style">font-size: 10pt</xsl:attribute>
	<xsl:attribute name="width">100%</xsl:attribute>
	<xsl:for-each select="./entry">
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
			<b><a>
			<xsl:attribute name="href">
			<xsl:for-each select="./link">
			<xsl:choose>
				<xsl:when test="@rel = 'alternate'">
					<xsl:value-of select="@href" />
				</xsl:when>
			</xsl:choose>
			</xsl:for-each>
			</xsl:attribute><xsl:value-of select="./title" />
			</a></b><br />
			</td>
		</tr>
		<tr>
			<td>
			Published: <xsl:value-of select="./created" />
			</td>
		</tr>
		</table><br />
		<div>
		<xsl:attribute name="style">font-size: 8pt</xsl:attribute>
		<xsl:value-of disable-output-escaping="yes" select="./content" />
		</div>
		</td>
	</tr>
	</xsl:for-each>
	</table><hr />

	<p>Copyright (C), Envent Holdings (Pty) Ltd t/a ENSIGHT. Visit <a href="http://support.ensight.co.uk">http://support.ensight.co.uk</a> for product support.</p>

</xsl:template>
</xsl:stylesheet>
