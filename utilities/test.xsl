<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
	exclude-result-prefixes="atom dxp"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:dxp="http://schemas.google.com/analytics/2009">

  <xsl:output method="xml"
    omit-xml-declaration="yes"
    encoding="UTF-8"
    indent="yes" />
    
  <xsl:template match="/atom:feed">
    <xsl:value-of select="atom:title"/>
    <fieldset>
      <select name="google_analytics_profile" id="google-analytics-profile">
        <xsl:apply-templates select="atom:entry"/>
      </select>
    </fieldset>
  </xsl:template>
  
  <xsl:template match="atom:entry">
    <option value="{dxp:tableId}"><xsl:value-of select="atom:title"/></option>
  </xsl:template>
  
</xsl:stylesheet>