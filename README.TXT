
TCPDF - README
============================================================
Description:
This is a PHP library which is used to plot a data line graph USING TCPDF library
TCPDF Library is included here. Source : link http://www.tcpdf.org

Installation guidelines:
*To run this code in your system, install apache 2 server 
*Install PHP 5 and dependencies (PHP 5 and above up to PHP 7.1 Compatible)

Installation of apache server
-------------------------------------------------------------------------------
sudo apt-get install -y apache2

Installation of PHP and dependencies
-------------------------------------------------------------------------------
sudo apt-get install -y php5
sudo apt-get install -y libapache2-mod-php5

If php module is not loaded 
--------------------------------------------------------------------------------
sudo service apache2 restart

For PDF generation
--------------------------------------------------------------------------------
sudo apt-get install -y php5-gd

Main Features:
*We are using tcpdf.php,tcpdf_autoconfig.php and tcpdf_extend.php files for generating a pdf with 
data line graph

*The values to plot in the graph and all other configurable parameters are given in the data_line_trending.php
* The function to get maximum and minimum values in the graph,and plotting of line graph also calling in this same php file.

*In the trending_graph_library.php file, two functions are there:
*First function(getRangeAndExtremeTrendingValues) is to get maximum and minimum value.
*Second function(dataTrendingGraph) is to plot the data line graph