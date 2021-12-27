CREATE OR REPLACE DATABASE biblioteca CHARACTER SET = 'keybcs2' COLLATE ='keybcs2_bin';
CREATE OR REPLACE TABLE biblioteca.Categorie(
    codCategoria varchar(4) PRIMARY KEY,
    descrizione varchar(16) NOT NULL
);
CREATE OR REPLACE TABLE biblioteca.Autori(
	codAutore varchar(16) PRIMARY KEY,
    nome varchar(16) NOT NULL,
    cognome varchar(16) NOT NULL,
    annoNascita int,
    biografia varchar(64)
);
CREATE OR REPLACE TABLE biblioteca.Amministratori(
	idAmministratore int AUTO_INCREMENT PRIMARY KEY,
    admin_mail varchar(32) NOT NULL,
    admin_password varchar(64) NOT NULL
);
CREATE OR REPLACE TABLE biblioteca.Utenti(
	codTessera varchar(8) PRIMARY KEY,
    nome varchar(16) NOT NULL,
    cognome varchar(16) NOT NULL,
    user_mail varchar(32) NOT NULL,
    dataRegistr datetime NOT NULL,
    codFiscale varchar(16) NOT NULL,
    user_password varchar(64) NOT NULL,
    idAmministratore int NOT NULL,
    CONSTRAINT FOREIGN KEY(`idAmministratore`) REFERENCES `Amministratori`(`idAmministratore`) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE OR REPLACE TABLE biblioteca.Libri(
	nInventario int AUTO_INCREMENT PRIMARY KEY,
    titolo varchar(32) NOT NULL,
    ISBN int NOT NULL,
    editore varchar(16),
    nScaffale int NOT NULL,
    codCategoria varchar(4) NOT NULL,
    codAutore varchar(16) NOT NULL,
    CONSTRAINT FOREIGN KEY(`codCategoria`) REFERENCES `Categorie`(`codCategoria`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FOREIGN KEY(`codAutore`) REFERENCES `Autori`(`codAutore`) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE OR REPLACE TABLE biblioteca.Prestiti(
	idPrestito int AUTO_INCREMENT PRIMARY KEY,
    codTessera varchar(8) NOT NULL,
    nInventario int NOT NULL,
    inizioPrestito datetime NOT NULL,
    finePrestito dateTime,
    classeAttuale varchar(4),
    CONSTRAINT FOREIGN KEY(`nInventario`) REFERENCES `Libri`(`nInventario`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FOREIGN KEY(`codTessera`) REFERENCES `Utenti`(`codTessera`) ON UPDATE CASCADE ON DELETE CASCADE
);