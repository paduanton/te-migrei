use temigrei;

CREATE TABLE `login_migracao` (
  `id` bigint not null auto_increment,
  `url_painel` varchar(255) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `sync_migracao` (
  `id` bigint not null auto_increment,
  `dominio` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `data` datetime NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


