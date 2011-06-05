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
    <h2>Google Analytics</h2>
    <div id="thirty-day-chart">
    	<table>
    		<thead>
    			<tr>
    				<th>Date</th>
    				<th>Visits</th>
    				<th>Pageviews</th>
    			</tr>
    		</thead>
        <tbody>
        	<xsl:apply-templates select="atom:entry"/>
        </tbody>
    	</table>
    </div>
  </xsl:template>
  
  <xsl:template match="atom:entry">
    <tr>
    	<td>
    		<xsl:value-of select="dxp:dimension[@name = 'ga:date']/@value"/>
    	</td>
    	<td>
    		<xsl:value-of select="dxp:metric[@name = 'ga:visits']/@value"/>
    	</td>
    	<td>
    		<xsl:value-of select="dxp:metric[@name = 'ga:pageviews']/@value"/>
    	</td>
    </tr>
  </xsl:template>
  
</xsl:stylesheet>