create database financas
default character set utf8
default collate utf8_general_ci;

create table usuario(
	cdUsuario bigint not null auto_increment,
    nmUsuario varchar(155) unique not null,
    dsSenha blob not null,
    dsEmail varchar(155) not null,
    cdPermissao enum('1','2','3','4','5') not null,
    cdStatus enum('1','2') not null,
    primary key(cdUsuario)
)default charset = 'utf8';

create table categoria(
	cdCategoria int not null auto_increment,
    nmCategoria varchar(255) unique not null,
    cdStatus enum('1','2') not null,
    primary key(cdCategoria)
)default charset = 'utf8';

create table orcamento(
	cdOrcamento bigint not null auto_increment,
    cdCategoria int not null,
    nmOrcamento varchar(205) not null,
    vlOrcamento decimal(10,2) not null,
    dia int not null,
    validade datetime not null,
    tpOrcamento enum('1','2') not null,
    cdStatus enum('1','2') not null,

    primary key(cdOrcamento)
)default charset = 'utf8';

create table conta(
	cdConta bigint not null auto_increment,
    cdOrcamento bigint not null,
    dsConta varchar(255) not null,
    vlConta decimal(10,2) not null,
    dtVencimento datetime,
    tpConta enum('1','2') not null,
    
    primary key(cdConta)
)default charset = 'utf8';

create table lancamento(
	cdLancamento bigint not null auto_increment,
    cdconta bigint not null,
    nmLancamento varchar(255) not null,
    vlLancamento decimal(10,2) not null,
    dtLancamento datetime,
    numNota bigint,
    nmAnexo varchar(255),
    tpLancamento enum('1','2'),
    
    primary key(cdLancamento)
)default charset = 'utf8';

create index index_nmUsuario on usuario(nmUsuario);
create index index_dsEmail on usuario(dsEmail);

create index index_nmCategoria on categoria(nmCategoria);

create index index_cdCategoria on orcamento(cdCategoria);
create index index_nmOrcamento on orcamento(nmOrcamento);

create index index_cdOrcamento on conta(cdOrcamento);
create index index_dtVencimento on conta(dtVencimento);

create index index_cdConta on lancamento(cdConta);
create index index_vlLancamento on lancamento(vlLancamento);
create index index_dtLancamento on lancamento(dtLancamento);