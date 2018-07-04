# tgist-taxonomy-browser

## Setting up a server

Requirements:

- MySQL
- PHP
- phpMyAdmin

Create the database, on a local machine it should be named tgist_NAME, on batcaves it should be named batcave1_tgist_NAME. Make sure to use the utf8mb4_bin collation.

For the local set up on OSX I used a recent MAMP installation with the Nginx server. It has some limitations on the size of the file that can be uploaded, which are different from the published by phpMyAdmin. First tried one big import for the SignalProcessing corpus, but on MAMP with Nginx the 1Mb gzipped archive was too big.

Instead did several exports and then imported them one by one:

1. Import the file `sql/schema.sql`.
2. Import `sql/technologies.sql.gz`.
3. Import `sql/hierarchy.sql.gz`.
4. Import `sql/relations-cooc.sql.gz`.
5. Import `sql/relations-term.sql.gz`.

The technologies and hierarchy files do not need to be gzipped for the SignalProcessing corpus, and nor do the term relations, but the cooccurrence relations can only be loaded if you gzip the file first.

NOTE: this is for copying an existing database, this really should be built from scratch with the SQL export from the taxonomy code.
