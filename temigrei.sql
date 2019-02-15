use temigrei;

CREATE TABLE `sync_migracao` (
  `id` bigint not null unique auto_increment,
  `host_cpanel` varchar(255) NOT NULL,
  `usuario_cpanel` varchar(255) NOT NULL,
  `senha_cpanel` varchar(255) NOT NULL,
  `dominio` varchar(255) not null,
  `status` varchar(255) NOT NULL,
  `data_solicitacao` datetime NOT NULL,
  `link_download` varchar(255),
  `analista_responsavel` varchar(255) not null,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


