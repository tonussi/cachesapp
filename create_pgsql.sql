drop table if exists caches cascade;
drop table if exists usuarios cascade;
drop table if exists usuarios_caches cascade;

create table caches(id serial not null,
  address_size_bits integer not null,
  control_size_bits integer not null,
  tag_size_bits integer not null,
  index_size_bits integer not null,
  offset_size_bits integer not null,
  ways_size integer not null,
  hash text);

create table usuarios_caches(idusuario int not null, idcache int not null);

create table usuarios(id serial not null,
  nome character varying(60) not null,
  curso character varying(120) not null);

create index usuarios_caches_idx on "usuarios_caches" (idusuario, idcache);
create index caches_idx on "caches" (id);
create index usuarios_idx on "usuarios" (id);

alter table usuarios_caches add constraint usuarios_caches_pkey primary key (idusuario, idcache);

alter table caches add constraint caches_pkey primary key (id);
alter table caches add constraint cache_config_sizes_check check (address_size_bits = tag_size_bits + index_size_bits + offset_size_bits);
alter table caches add constraint control_size_check check (control_size_bits <= 3 and control_size_bits >= 0);

alter table usuarios add constraint usuarios_pkey primary key (id);
alter table usuarios add constraint usuarios_nome_check check (nome::text <> ''::text);

alter table usuarios_caches add constraint idusuarios_fkey foreign key (idusuario)
references usuarios (id) match simple on update no action on delete no action;

alter table usuarios_caches add constraint idcache_fkey foreign key (idcache)
references caches (id) match simple on update no action on delete no action;

CREATE OR REPLACE FUNCTION hash_update_tg() RETURNS trigger AS $$
BEGIN
    IF tg_op = 'INSERT' OR tg_op = 'UPDATE' THEN
        NEW.hash = md5((NEW.offset_size_bits + NEW.tag_size_bits + NEW.ways_size +  NEW.control_size_bits)::text);
        RETURN NEW;
    END IF;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER caches_hash_update 
BEFORE INSERT OR UPDATE ON caches 
FOR EACH ROW EXECUTE PROCEDURE hash_update_tg();

insert into caches (address_size_bits, control_size_bits, tag_size_bits, index_size_bits, offset_size_bits, ways_size) values (36, 1, 13, 10, 13, 1);
insert into caches (address_size_bits, control_size_bits, tag_size_bits, index_size_bits, offset_size_bits, ways_size) values (32, 1, 13, 9, 10, 2);
insert into caches (address_size_bits, control_size_bits, tag_size_bits, index_size_bits, offset_size_bits, ways_size) values (32, 1, 13, 11, 8, 1);
insert into caches (address_size_bits, control_size_bits, tag_size_bits, index_size_bits, offset_size_bits, ways_size) values (43, 1, 24, 0, 19, 32);
insert into caches (address_size_bits, control_size_bits, tag_size_bits, index_size_bits, offset_size_bits, ways_size) values (32, 1, 4, 14, 14, 1);

insert into usuarios(nome, curso) values ('Lucas', 'Ciência da Computação');
insert into usuarios(nome, curso) values ('Marcela', 'Ciência da Computação');

insert into usuarios_caches(idusuario, idcache) values (1, 1);
insert into usuarios_caches(idusuario, idcache) values (1, 2);
insert into usuarios_caches(idusuario, idcache) values (1, 3);

insert into usuarios_caches(idusuario, idcache) values (2, 1);
insert into usuarios_caches(idusuario, idcache) values (2, 2);
insert into usuarios_caches(idusuario, idcache) values (2, 3);

select * from usuarios_caches;