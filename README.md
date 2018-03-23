# RAMCI Api Package

This lLibrary allows to query the RMACI B2B API of  for registered users. You need the access details that were provided to you to make any calls to the API.

# Configuration

## .env file

Configuration via the .env file currently allows the following variables to be set:

- RAMCI\_BASE\_URL : the base URL for the API endpoint WITHOUT the command (report/xml/pdf)
- RAMCI\_USERNAME : the username to access the API
- RAMCI\_PASSWORD : the password to ccess the API

### Example:
if the urls you have to generate the reports are
- http://api.endpoint/url/report
- http://api.endpoint/url/xml
- http://api.endpoint/url/pdf

and your username is demouser with password demoPassword then: 

- RAMCI\_BASE\_URL='http://api.endpoint/url/'
- RAMCI\_USERNAME=demouser 
- RAMCI\_PASSWORD=demoPassword
