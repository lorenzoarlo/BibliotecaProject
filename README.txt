1 - Andare su phpMyAdmin e inserire nell'SQL il seguente codice:
CREATE OR REPLACE USER "Bibliotecatore"@'%' IDENTIFIED BY "pwd_in_chiaro";
CREATE OR REPLACE DATABASE biblioteca CHARACTER SET = 'keybcs2' COLLATE ='keybcs2_bin';

2 - Andare su phpMyAdmin e inserire nell'SQL il seguente codice:
REVOKE ALL PRIVILEGES ON *.* FROM 'Bibliotecatore'@'%'; 
GRANT ALL PRIVILEGES ON *.* TO 'Bibliotecatore'@'%' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;

3 - Andare su CONTROL PANEL/op-controlPanel.html e cliccare su RESET DATABASE e poi su FILL DATABASE

4 - 
Esempi di utenti:
-> Username: a0000000
-> Password: tmp1
Esempi di amministratori:
-> Username: admin
-> Password: a