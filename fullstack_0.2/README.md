# Power And Go - Dashboard

Figyelem! Az `.env.example` fájl a `backend` mappában található, és a `.env` fájl is ott lesz, mert a backend szerves része. Mivel ezt a többi container is használja, így egy hivatkozás jön létre indításkor a projektmappában.

## Indítás

A rendszer inicializálását és az első indítását a `start.sh` végzi.

 A `migráció`, `seedelés` és a `tesztesetek` egyetemlegesen, automatikusan futnak le.
```bash
sh start.sh
```
parancs futtatásával.
## Leállítás

```bash
docker compose stop
```
## Újraindítás / Újragenerálás
Futtatás után a teljes program újraindításával `megváltoztatott adatokkal`, ugyanakkor azonos struktúra felépítéssel a `terminálban` egy paranccsal:

```bash
sh start.sh
```

## Eltávolítás
```bash
docker compose down -v
```

 - A `-v` hatására a köteteket is törli, így az adatbázisban tárolt adatok is megszűnnek.