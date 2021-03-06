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
    <div id="top-pages">
    <h3>Top Pages</h3>
    	<table>
    		<thead>
    			<tr>
    				<th>Top pages (past 30 days)</th>
    				<th>Views</th>
    			</tr>
    		</thead>
        <tbody>
        	<xsl:apply-templates select="atom:entry[position() &lt;= 100]"/>
        </tbody>
    	</table>
    </div>
  </xsl:template>
  
  <xsl:template match="atom:entry">
    <tr>
    	<td>
    		<xsl:value-of select="dxp:dimension[@name = 'ga:pageTitle']/@value"/>
    	</td>
    	<td>
    		<xsl:value-of select="dxp:metric[@name = 'ga:pageviews']/@value"/>
    	</td>
    </tr>
  </xsl:template>
  
</xsl:stylesheet>