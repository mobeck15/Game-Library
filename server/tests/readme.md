command> 
- phpunit .\htdocs\Game-Library\server\tests
- phpunit --coverage-text .\htdocs\Game-Library\server\tests
- phpunit --coverage-html .\htdocs\glcoverage.htm
- phpunit --coverage-text .\htdocs\Game-Library\server\tests --coverage-html glcoverage.htm

---
Getting PHPunit to work in XAMPP:
1. Download PHPunit.phar from this site
   - Downloads - https://phpunit.de/getting-started/phpunit-9.html
   - Help Docs - https://phpunit.readthedocs.io/en/9.5/index.html
2. Copy the file to XAMPP/PHP folder
3. Rename or delete the existing phpunit file (no extention)
4. Rename the downloaded file to phpunit (no extention)
5. If you want code coverage to work, you need to install xDebug
   - Official Downlaods - https://xdebug.org/docs/install
   - Instructions - https://gist.github.com/odan/1abe76d373a9cbb15bed
   - Config Documentation - https://xdebug.org/docs/all_settings#mode
   1. Download the dll file
   2. Copy it to php/ext
   3. Add the [XDEBUG] section to php.ini
   4. Add xdebug.mode=coverage at the end
