CREATE TABLE tx_hubic_domain_model_account (
	uid              INT(11)                                NOT NULL AUTO_INCREMENT,
	pid              INT(11) DEFAULT '0'                    NOT NULL,
	name             VARCHAR(50) DEFAULT ''                 NOT NULL,
	client_id        VARCHAR(80) DEFAULT ''                 NOT NULL,
	client_secret    VARCHAR(80) DEFAULT ''                 NOT NULL,
	access_token     VARCHAR(80) DEFAULT ''                 NOT NULL,
	crdate           INT(11) DEFAULT '0'                    NOT NULL,
	deleted          TINYINT(4) DEFAULT '0'                 NOT NULL,
	hidden           TINYINT(4) DEFAULT '0'                 NOT NULL,
	tstamp           INT(11) DEFAULT '0'                    NOT NULL,
	cruser_id        INT(11) DEFAULT '0'                    NOT NULL,
	starttime        INT(11) DEFAULT '0'                    NOT NULL,
	endtime          INT(11) DEFAULT '0'                    NOT NULL,
	sorting          INT(11) DEFAULT '0'                    NOT NULL,
	sys_language_uid INT(11) DEFAULT '0'                    NOT NULL,
	l18n_parent      INT(11) DEFAULT '0'                    NOT NULL,
	l18n_diffsource  MEDIUMTEXT,
	access_group     INT(11) DEFAULT '0'                    NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);
