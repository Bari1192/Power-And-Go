import random
import datetime

# Random Rendszám generálás
def generate_license_plate():
    if random.random() < 0.5:
        return ''.join(random.choices('ABCDEFGHIJKLMNOPQRSTUVWXYZ', k=3)) + '-' + ''.join(random.choices('0123456789', k=3))
    else:
        return ''.join(random.choices('ABCDEFGHIJKLMNOPQRSTUVWXYZ', k=4)) + '-' + ''.join(random.choices('0123456789', k=3))

# Generate random date
def generate_random_date(start_year, end_year):
    start_date = datetime.date(start_year, 1, 1)
    end_date = datetime.date(end_year, 12, 31)
    time_between_dates = end_date - start_date
    days_between_dates = time_between_dates.days
    random_number_of_days = random.randrange(days_between_dates)
    return start_date + datetime.timedelta(days=random_number_of_days)

# Generate SQL insert statements for Kategoriak
def generate_kategoriak_data():
    data = [
        [1, 0, 'e-Up!'],
        [2, 1, 'e-Up!'],
        [3, 1, 'Citigo'],
        [4, 2, 'Niro'],
        [5, 3, 'Vivaro-e'],
        [6, 4, 'Kangoo Z.E.']
    ]
    sql_statements = []
    for row in data:
        sql_statements.append(f"({row[0]}, {row[1]}, '{row[2]}'),")
    return sql_statements

# Generate SQL insert statements for Autok
def generate_autok_data(num_records):
    sql_statements = []
    gyartmany_options = ["Skoda", "VW", "KIA", "Opel", "Renault"]
    gyartmany_weights = [0.1, 0.6, 0.1, 0.1, 0.1]
    tipus_options = {
        "Skoda": "Citigo e iV",
        "VW": "e-up!",
        "KIA": "Niro EV",
        "Opel": "Vivaro-e",
        "Renault": "Kangoo Z.E."
    }
    teljesitmeny_options = {
        "Skoda": [36],
        "VW": [18, 36],
        "KIA": [65],
        "Opel": [50],
        "Renault": [45]
    }
    for i in range(1, num_records + 1):
        gyartmany = random.choices(gyartmany_options, weights=gyartmany_weights, k=1)[0]
        tipus = tipus_options[gyartmany]
        rendszam = generate_license_plate()
        teljesitmeny = random.choice(teljesitmeny_options[gyartmany])
        teljesitmeny_str = str(teljesitmeny) + ' kW'
        gyorsulas = round(random.uniform(7.0, 15.0), 1)
        vegsebesseg = random.randint(120, 180)
        gumimeret = f"{random.randint(165, 215)}/{random.randint(50, 70)} R{random.randint(14, 18)}"
        hatotav = random.randint(150, 350)
        gyartasi_ev = random.choice(['2020', '2021', '2022', '2023'])
        sql_statements.append(f"('{gyartmany}', '{tipus}', '{rendszam}', '{teljesitmeny_str}', {gyorsulas}, {vegsebesseg}, '{gumimeret}', {hatotav}, '{gyartasi_ev}'),")
    return sql_statements

# Generate SQL insert statements for Felszereltseg
def generate_felszereltseg_data(num_records):
    sql_statements = []
    for i in range(1, num_records + 1):
        if i % 5 == 0:  # Opel, KIA, Renault all 'igen'
            tolatokamera, m_kormany, savtarto, tempomat = 'igen', 'igen', 'igen', 'igen'
        elif i % 3 == 0:  # Skoda 70% 'igen'
            if random.random() < 0.7:
                tolatokamera, m_kormany, savtarto, tempomat = 'igen', 'igen', 'igen', 'igen'
            else:
                tolatokamera, m_kormany, savtarto, tempomat = 'nem', 'igen', 'nem', 'igen'
        else:  # VW random 'igen' or 'nem'
            if random.random() < 0.5:
                tolatokamera, m_kormany, savtarto, tempomat = 'igen', 'igen', 'igen', 'igen'
            else:
                tolatokamera, m_kormany, savtarto, tempomat = 'nem', 'nem', 'nem', 'nem'
        sql_statements.append(f"({i}, '{tolatokamera}', '{m_kormany}', '{savtarto}', '{tempomat}'),")
    return sql_statements

# Generate SQL insert statements for Felhasznalok and Szemely
def generate_users_and_persons_data(num_records):
    users_sql_statements = []
    persons_sql_statements = []
    first_names = ["John", "Jane", "Alice", "Bob", "Charlie", "Diana"]
    last_names = ["Doe", "Smith", "Johnson", "Williams", "Brown", "Jones"]
    domains = ["gmail.com", "yahoo.com", "outlook.com", "hotmail.com"]
    elofiz_kat_options = ['0', '1', '2', '3']
    elofiz_kat_weights = [0.2, 0.3, 0.1, 0.4]
    
    for i in range(1, num_records + 1):
        first_name = random.choice(first_names)
        last_name = random.choice(last_names)
        username = first_name[:3].lower() + last_name[:3].lower() + str(random.randint(1000, 9999))
        email = f"{first_name.lower()}.{last_name.lower()}@{random.choice(domains)}"
        phone = '+36' + ''.join(random.choices('0123456789', k=9))
        birth_date = generate_random_date(1959, 2005)
        license_number = ''.join(random.choices('ABCDEFGHIJKLMNOPQRSTUVWXYZ', k=2)) + ''.join(random.choices('0123456789', k=6))
        password = ''.join(random.choices('0123456789', k=10))
        jelszo_masodik_utolso = password[1] + password[-1]
        jogositvany_ervenyesseg = generate_random_date(2013, 2015)
        jogositvany_lejarata = generate_random_date(2023, 2025)
        szig_szam = ''.join(random.choices('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', k=8))
        elofiz_kat = random.choices(elofiz_kat_options, weights=elofiz_kat_weights, k=1)[0]

        persons_sql_statements.append(f"('{license_number}', '{jogositvany_ervenyesseg}', '{jogositvany_lejarata}', '{last_name}', '{first_name}', '{szig_szam}', '{password}', '{birth_date}', '{phone}', '{email}');")
        users_sql_statements.append(f"('{username}', '{elofiz_kat}', '{license_number}', '{jelszo_masodik_utolso}'),")
    
    return persons_sql_statements, users_sql_statements

# Generate SQL insert statements for Berles
def generate_berles_data(num_records):
    sql_statements = []
    for i in range(1, num_records + 1):
        kat_id = random.randint(1, 6)
        felh_id = random.randint(1, 500)
        auto_id = random.randint(1, 500)
        berles_kezd = datetime.datetime(2023, 1, 1) + (datetime.datetime.now() - datetime.datetime(2023, 1, 1)) * random.random()
        if random.random() < 0.2:  # 20% ongoing rentals
            berles_veg = 'NULL'
        else:
            berles_veg = berles_kezd + datetime.timedelta(hours=random.randint(1, 72))
            berles_veg = f"'{berles_veg}'"
        sql_statements.append(f"({kat_id}, {felh_id}, {auto_id}, '{berles_kezd}', {berles_veg}),")
    return sql_statements

# Generate SQL insert statements for all tables
autok_sql = generate_autok_data(500)
felszereltseg_sql = generate_felszereltseg_data(500)
persons_sql, users_sql = generate_users_and_persons_data(500)
berles_sql = generate_berles_data(275)  # Sum of all rental requests

# Minden SQL sor kiírása (újra felülírása) a '.sql' fájlba:
mentes_helye = "C:/Users/barab/Desktop/Power-And-Go/Adatbázis Szerkezeti felépítése/kategoriak.sql"

try:
    with open(mentes_helye, "w") as file:
        file.write("\n".join(kategoriak_sql + autok_sql + felszereltseg_sql + persons_sql + users_sql + berles_sql))
    print(f"SQL file successfully generated at {mentes_helye}")
except Exception as e:
    print(f"Error: {e}")