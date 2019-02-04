use temigrei;

CREATE TABLE `sync_migracao` (
  `id` bigint not null unique auto_increment,
  `url_painel` varchar(255) NOT NULL,
  `usuario_cpanel` varchar(255) NOT NULL,
  `senha_cpanel` varchar(255) NOT NULL,
  `dominio` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `data` datetime NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


