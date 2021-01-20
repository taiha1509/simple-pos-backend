# simple-pos-backend
backend of simple pos project in magestore internship

to run this project:
clone project
composer install
bin/magento setup:upgrade


some notes:
  must set the prefix of database is m2 (because some joining collection use the name as m2_customer)
  must comment code check authorization with customer when you place order, test place order by posman and get the exception => ask google :v
  
  
