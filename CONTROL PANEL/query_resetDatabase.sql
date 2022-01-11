CREATE OR REPLACE DATABASE biblioteca CHARACTER SET = 'keybcs2' COLLATE ='keybcs2_bin';
CREATE OR REPLACE TABLE biblioteca.Categorie(
    codiceCategoria varchar(4) PRIMARY KEY,
    descrizioneCategoria varchar(16) NOT NULL UNIQUE
);
CREATE OR REPLACE TABLE biblioteca.Autori(
	codiceAutore varchar(16) PRIMARY KEY,
    nomeAutore varchar(16) NOT NULL,
    cognomeAutore varchar(16) NOT NULL,
    annoNascitaAutore int,
    biografiaAutore varchar(64)
);
CREATE OR REPLACE TABLE biblioteca.Amministratori(
	idAmministratore int AUTO_INCREMENT PRIMARY KEY,
    mailAmministratore varchar(32) NOT NULL UNIQUE,
    passwordAmministratore varchar(64) NOT NULL,
    passwordInChiaroAmministratore varchar(16) NOT NULL
);
CREATE OR REPLACE TABLE biblioteca.Utenti(
	codiceTessera varchar(8) PRIMARY KEY,
    nomeUtente varchar(16) NOT NULL,
    cognomeUtente varchar(16) NOT NULL,
    mailUtente varchar(32) NOT NULL UNIQUE,
    dataRegistrazioneUtente datetime NOT NULL,
    codiceFiscale varchar(16) NOT NULL UNIQUE,
    passwordUtente varchar(64) NOT NULL,
    idAmministratore int NOT NULL,
    passwordInChiaroUtente varchar(32) NOT NULL,
    CONSTRAINT FOREIGN KEY(`idAmministratore`) REFERENCES `Amministratori`(`idAmministratore`) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE OR REPLACE TABLE biblioteca.Libri(
	numeroInventario int AUTO_INCREMENT PRIMARY KEY,
    titolo varchar(64) NOT NULL,
    ISBN int NOT NULL,
    editore varchar(16),
    numeroScaffale int NOT NULL,
    codiceCategoria varchar(4) NOT NULL,
    codiceAutore varchar(16) NOT NULL,
    CONSTRAINT FOREIGN KEY(`codiceCategoria`) REFERENCES `Categorie`(`codiceCategoria`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FOREIGN KEY(`codiceAutore`) REFERENCES `Autori`(`codiceAutore`) ON UPDATE CASCADE ON DELETE CASCADE
);
CREATE OR REPLACE TABLE biblioteca.Prestiti(
	idPrestito int AUTO_INCREMENT PRIMARY KEY,
    codiceTessera varchar(8) NOT NULL,
    numeroInventario int NOT NULL,
    inizioPrestito datetime NOT NULL,
    finePrestito dateTime,
    classeAttuale varchar(4),
    CONSTRAINT FOREIGN KEY(`numeroInventario`) REFERENCES `Libri`(`numeroInventario`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FOREIGN KEY(`codiceTessera`) REFERENCES `Utenti`(`codiceTessera`) ON UPDATE CASCADE ON DELETE CASCADE
);