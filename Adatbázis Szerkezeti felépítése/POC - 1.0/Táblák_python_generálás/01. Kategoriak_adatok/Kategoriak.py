# # # ! IMPORTANT NOTES ! # # # 
# A python fájl lokális helyére menti az elkészült .sql fájlt is.
# Az utolsó elem kivételével soronként vessző lesz az adatbeszúrás végén, így:
# Az utolsó sornál a ';' miatt külön kezeljük.

import os 

def kategoria_generalas():
    adatok = [
        [1, 0, 'e-Up!'],
        [2, 1, 'e-Up!'],
        [3, 1, 'Citigo'],
        [4, 2, 'Niro'],
        [5, 3, 'Vivaro-e'],
        [6, 4, 'Kangoo Z.E.']
    ]
    sql_kod_sorokban = []
    for sor in adatok[:-1]:     
        sql_kod_sorokban.append(f"({sor[0]}, {sor[1]}, '{sor[2]}'),")
    utolso_sor = adatok[-1]
    sql_kod_sorokban.append(f"({utolso_sor[0]}, {utolso_sor[1]}, '{utolso_sor[2]}');") 
    return sql_kod_sorokban

kategoriak_adatok = kategoria_generalas()


# Minden SQL sor kiírása (újra felülírása) a '.sql' fájlba:
mentes_helye = os.path.join(os.path.dirname(os.path.abspath(__file__)), "Kategoriak.sql")

try:
    with open(mentes_helye, "w") as elkeszult_fajl:
        elkeszult_fajl.write("INSERT INTO Kategoriak (kat_id, kategoria_besorolas, kat_modell_nev) VALUES"+"\n")
        elkeszult_fajl.write("\n".join(kategoriak_adatok))
    print(f"A fájl legenerálása megörtént ide: {mentes_helye}")
except Exception as e:
    print(f"Hiba történt a fájl létrehozásakor: {e}")
    
