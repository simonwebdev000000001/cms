DROP TABLE IF EXISTS articles;
CREATE TABLE articles
(
  id              smallint unsigned NOT NULL auto_increment,
  publicationDate date NOT NULL,                              # Когда статья опудликована
  title           varchar(255) NOT NULL,                      # Полный заголовок статьи
  summary         text NOT NULL,                              # Резюме статьи
  content         mediumtext NOT NULL,                        # HTML содержание статьи

  PRIMARY KEY     (id)
);


CREATE TABLE `cms`.`users` ( `id_user` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `fio` VARCHAR(255) NOT NULL , `login` VARCHAR(255) NOT NULL ,
  `password` VARCHAR(255) NOT NULL , `phone` BIGINT(13) NOT NULL ,
  `adress` VARCHAR(255) NOT NULL , PRIMARY KEY (`id_user`),
  UNIQUE  `user_login` (`login`))
  ENGINE = InnoDB CHARACTER SET cp1251 COLLATE cp1251_bin;