import os
import random

# # # # # # # # # # # # # # # # # # # # #   || IMPORTANT NOTES ||   # # # # # # # # # # # # # # # # # # # # # # # # # #  # # # # # # # # # # # #

        # A 'generalt_adatsorok_szama' változó értékét átállítva változtatható a generált autók mennyisége!
        # Generáláskor az felszereltségek megoszlása gyártó és típusonként:
            # Renault, Opel, KIA esetében mindegyik extrával rendelkeznek.
            # Skoda Citigo-k esetében 70%-a mindegyik extrával rendelkeznek, 30%-a csak az extrák felével.
            # VW esetében - teljesítménytől függetlenül - 50%-a mindegyik extrával rendelkezik, 50% semmilyen extrával sem.
        # Tipusonként más a teljesítmény, gumi, végsebesség, stb. így külön, de egymásra épülve kezeljük.
        # Rendszám új & régi esetében eltérő, így külön függvényben random generált rendszám (újak ált. 'AA'-val kezdődnek).
        # A gyártási év RANDOM évszám 2020 és 2023 között.
        
 # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
generalt_adatsorok_szama = 100  # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
 # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

# RENDSZÁM
def rendszam_generalas():
    if random.random() < 0.5:
        return ''.join(random.choices('ABCDEFGHIJKLMNOPQRSTUVWXYZ', k=3)) + '-' + ''.join(random.choices('0123456789', k=3))
    else:
        return 'AA' + ''.join(random.choices('ABCDEFGHIJKLMNOPQRSTUVWXYZ', k=2)) + '-' + ''.join(random.choices('0123456789', k=3))

# TÖBBI ADAT
def autok_adat_generalasa(generalt_adatsorok_szama):
    sql_kod_sorokban = []
    gyartmany_options = ["Skoda", "VW", "KIA", "Opel", "Renault"]  # Ez alapján fog bekerülni az adott auto helyes adatai. // "tipus_teljesitmeny[Skoda]"
    gyarto_tipus = {
        "Skoda": "Citigo e iV",
        "VW": "e-up!",
        "KIA": "Niro EV",
        "Opel": "Vivaro-e",
        "Renault": "Kangoo Z.E."
    }
    tipus_teljesitmeny = {
        "Skoda": [36],
        "VW": [18, 36],
        "KIA": [65],
        "Opel": [50],
        "Renault": [45]
    }
    tipus_vegsebesseg = {
        "Skoda": [130],
        "VW": [130],
        "KIA": [167],
        "Opel": [192],
        "Renault": [130]
    }
    tipus_gumimeret = {
        "VW": '165/65 R15',
        "Skoda": '165/65 R16',
        "KIA": '165/65 R17',
        "Opel": '165/65 R16',
        "Renault": '165/65 R15'
    }
    tipus_hatotav = {
                                            # VW -nél 2 érték szerint kell vizsgálni!!!!
        "VW": {18: '130 km', 36: '300 km'},
        "Skoda": '300 km',
        "KIA": '350 km',
        "Opel": '320 km',
        "Renault": '285 km'
    }
    tipus_gyorsulas={
         "VW": 12.4,
        "Skoda": 12.3,
        "KIA":7.8,
        "Opel":11.0,
        "Renault":22.3
    }
    for i in range(1, generalt_adatsorok_szama):
        gyartmany = random.choice(gyartmany_options)
        tipus = gyarto_tipus[gyartmany]
        rendszam = rendszam_generalas()
        teljesitmeny = random.choice(tipus_teljesitmeny[gyartmany])
        gyorsulas = tipus_gyorsulas[gyartmany]
        vegsebesseg = random.choice(tipus_vegsebesseg[gyartmany])
        gumimeret = tipus_gumimeret[gyartmany]
        if gyartmany == "VW":
            hatotav = tipus_hatotav[gyartmany][teljesitmeny]
        else:
            hatotav = tipus_hatotav[gyartmany]
        gyartasi_ev = random.choice(['2020', '2021', '2022', '2023'])
        sql_kod_sorokban.append({'rendszam': rendszam, 'gyartmany': gyartmany, 'teljesitmeny': teljesitmeny, 
        'sql':
            f"('{gyartmany}', '{tipus}', '{rendszam}', {teljesitmeny}, {gyorsulas}, {vegsebesseg}, '{gumimeret}', {hatotav.split()[0]}, '{gyartasi_ev}')"})
    
    # Az UTOLSÓ SOR hozzáadása ';'-vel || Return előtti sor végén látod.
    gyartmany = random.choice(gyartmany_options)
    tipus = gyarto_tipus[gyartmany]
    rendszam = rendszam_generalas()
    teljesitmeny = random.choice(tipus_teljesitmeny[gyartmany])
    gyorsulas = round(random.uniform(7.0, 15.0), 1)
    vegsebesseg = random.choice(tipus_vegsebesseg[gyartmany])
    gumimeret = tipus_gumimeret[gyartmany]
    if gyartmany == "VW":
        hatotav = tipus_hatotav[gyartmany][teljesitmeny]
    else:
        hatotav = tipus_hatotav[gyartmany]
    gyartasi_ev = random.choice(['2020', '2021', '2022', '2023'])
    # sql_kod_sorokban.append(f"('{gyartmany}', '{tipus}', '{rendszam}', {teljesitmeny}, {gyorsulas}, {vegsebesseg}, '{gumimeret}', {hatotav.split()[0]}, '{gyartasi_ev}');")
            # Helyette: az Autók adatait külön struktúrában tárolni, mert megkönnyíti az összekapcsolást a felszereltséggel!
            # Az Autók adatainak legenerálása után egyszerűbb az adatokra hivatkozni a felszereltség generálásánál.
            # De egyébként simán "átadható" lenne az 'sql_kod_sorokban' is, mert az is egy lista amm...
    sql_kod_sorokban.append({'rendszam': rendszam, 'gyartmany': gyartmany, 'teljesitmeny': teljesitmeny,
        'sql':
            f"('{gyartmany}', '{tipus}', '{rendszam}', {teljesitmeny}, {gyorsulas}, {vegsebesseg}, '{gumimeret}', {hatotav.split()[0]}, '{gyartasi_ev}');"})

    return sql_kod_sorokban

##############################
### FELSZERELTSÉG FÜGGVÉNY ###
##############################

    # <---- REFAKTORÁLÁS ----> || utolsó sor vizsgálatára ||

def felszereltseg_generalasa(legeneralt_teljes_autok_lista):
    sql_kod_sorokban = []
    for i, auto in enumerate(legeneralt_teljes_autok_lista):        # Azért kell az 'enumarate' mert így hozzáférek az indexhez és az elemhez is a listában!!!
        if auto['gyartmany'] in ['Opel', 'KIA', 'Renault']: # Minden OPEL, KiA, Renault 'fullos'
            tolatokamera, m_kormany, savtarto, tempomat = 'igen', 'igen', 'igen', 'igen'
        elif auto['gyartmany'] == 'Skoda':
            if random.random() < 0.7:       #Csak a 70% fullos a Skodáknak
                tolatokamera, m_kormany, savtarto, tempomat = 'igen', 'igen', 'igen', 'igen'
            else:                           # a 30%-a csak "félig"
                tolatokamera, m_kormany, savtarto, tempomat = 'nem', 'igen', 'nem', 'igen'
        elif auto['gyartmany'] == 'VW':
            if auto['teljesitmeny'] == 36:
                tolatokamera, m_kormany, savtarto, tempomat = 'igen', 'igen', 'igen', 'igen'
            else:
                tolatokamera, m_kormany, savtarto, tempomat = 'nem', 'nem', 'nem', 'nem'
                
        if i == len(legeneralt_teljes_autok_lista) - 1:  # -1 az utolsó sor így.
            sql_kod_sorokban.append(f"('{auto['rendszam']}', '{tolatokamera}', '{m_kormany}', '{savtarto}', '{tempomat}');")
        else:
            sql_kod_sorokban.append(f"('{auto['rendszam']}', '{tolatokamera}', '{m_kormany}', '{savtarto}', '{tempomat}'),")
   
    return sql_kod_sorokban

##############################################################################################
legeneralt_teljes_autok_lista = autok_adat_generalasa(generalt_adatsorok_szama)

Autok_adatok = autok_adat_generalasa(generalt_adatsorok_szama)
felszereltseg_adatok = felszereltseg_generalasa(legeneralt_teljes_autok_lista)
################## F Á J L    M E N T É S    H E L Y E ########################################
autok_mentes_helye = os.path.join(os.path.dirname(os.path.abspath(__file__)), "autok_adatgeneralas_pythonnal.sql")
felszereltseg_mentes_helye = os.path.join(os.path.dirname(os.path.abspath(__file__)), "felszereltseg_adatgeneralas_pythonnal.sql")
###############################################################################################

###################
# MENTÉS & KIÍRÁS #
###################

# <---- REFAKTORÁLÁS ----> || Csak az 'sql' részét írja így a fájlba - kicsit megcsavartan ugyan -, a többit NEM [no-duplicate]!
try:
    with open(autok_mentes_helye, "w") as autok_elkeszult_fajl:
        autok_elkeszult_fajl.write("INSERT INTO Autok (gyartmany, tipus, rendszam, teljesitmeny, gyorsulas, vegsebesseg, gumimeret, hatotav, gyartasi_ev) VALUES\n")
        autok_elkeszult_fajl.write(",\n".join(
            auto['sql'] for auto in legeneralt_teljes_autok_lista[:-1]
        ))
        autok_elkeszult_fajl.write(f",\n{legeneralt_teljes_autok_lista[-1]['sql']}")
    print(f"A fájl legenerálása megörtént ide: {autok_mentes_helye}")
    
    with open(felszereltseg_mentes_helye, "w") as felszereltseg_elkeszult_fajl:
        felszereltseg_elkeszult_fajl.write("INSERT INTO Felszereltseg (rendszam_FK, tolatokamera, m_kormany, savtarto, tempomat) VALUES\n")
        felszereltseg_elkeszult_fajl.write("\n".join(felszereltseg_adatok))
    print(f"A fájl legenerálása megörtént ide: {felszereltseg_mentes_helye}")
    
except Exception as e:
    print(f"A fájl legenerálása vmi hiba lépett fel: {e}")