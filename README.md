# COMPANIES AND EMPLOYEES

CRUD dla companies i employees (Symfony)

## Spis treści
- [Opis](#opis)
- [Wymagania](#wymagania)
- [Instalacja](#instalacja)
- [Użycie](#użycie)
- [Endpoints](#endpoints)

## Opis
 - ``base url endpointów: 127.0.0.1:81``

## Wymagania
Lista wszystkich zależności, które muszą być zainstalowane, aby projekt działał:

    - Docker 

Wykonywanie CRUD np. w ``POSTMAN``

## Instalacja
- będąc w katalogu projektu, uruchamiamy kontenery:

  ``/katalog-projektu docker compose up``


- listowanie uruchomionych kontenerów:

  ``docker ps``


- przechodzimy do kontenera php:

  ``docker exec -it ID_KONTENERA (np. 3 pierwsze znaki) /bin/bash``


- w kontenerze php instalujemy zależności:

  ``/var/www/html# composer install``

## Użycie
- towrzenie bazy danych (w kontenerze php):
  ``/var/www/html# php bin/console doctrine:database:create``

- uruchomienie migracji (w kontenerze php):
  ``/var/www/html# php bin/console doctrine:migrations:migrate``


- wypełnienie tabel domyślnymi danymi (konieczne do wykonania, również w kontenerze php):
  ``/var/www/html# php bin/console app:initialize-system-default-data``


- wypełnienie tabel fake`owymi danymi (w kontenerze php):
  ``/var/www/html# php bin/console app:initialize-system-fake-data``



- logowanie (JWT):

  url ``/api/login``

  payload: ``{ login: admin@example.com, password: admin123!}`` (z domyślnych danych)

  Powyższe dane pozwalają na dostęp do wszystkich endpointów.

  Każdy endpoint wymaga tokenu zwracanego po zalogowaniu. 

  --

  payload: ``{ login: jan.nowakowski@example.com, password: jan.nowakowski@example.com }`` (z fake`owych danych)

  powyższe dane pozwalają na dostęp do listowania tylko pracowników
 
  --
- PHPMyAdmin
  
  ``url: http://127.0.0.1:8081/``
  ``username: user``
  ``password: password``

## Endpoints

- logowanie: ``/api/login``


- get companies [GET]:
``/api/companies?includes=employees,contacts,address&sortBy=name&sortDirection=asc&phrase=Wit``


- get company [GET]:
``/api/companies/54c20316-bef8-429d-a9ae-44ddb90149a3``


- delete company [DELETE]: 
``/api/companies/3e005155-b1ec-4ff6-9455-87c76a1f99dd``


- create company [POST]:
  ``api/companies``

   payload:
  
   pola `phones`, `emails` nie są wymagane
 ```
{
    "name": "Urbex",
    "nip": "4969699770",
    "phones": [
        "155-555-555",
        "255-555-555",
        "355-555-555"
    ],
    "emails": [
        "futuretechnnology_1@example.com",
        "futuretechnnology_2@example.com",
        "futuretechnnology_3@example.com"
    ],      
    "address": {
        "street": "street",
        "postcode": "12-123",
        "city": "Warszawa",
        "country": "Polska"
    }
}
  ```

- update company [PUT]:

  payload:

  pola `phones`, `emails` nie są wymagane
``/api/companies/3e005155-b1ec-4ff6-9455-87c76a1f99dd``

```
{
    "name": "Firma \"Complex 3uuu\"",
    "nip": "1227053290",
    "phones": [
        "715-555-555",
        "725-555-555",
        "735-555-555"
    ],
    "emails": [
        "email71@example.com",
        "email72@example.com",
        "email73@example.com"
    ],      
    "address": {
        "street": "Wiejska 11",
        "postcode": "11-111",
        "city": "Gdańsk",
        "country": "Polska"
    }
}
```

- get employees [GET]:
``/api/employees?includes=company,address,contacts&pageSize=2``   


- get employee [GET]:
  ``/api/employees/0337fe08-1cb3-4538-9204-0aab16d4918f``

- delete employee [DELETE]: 
``/api/employees/00c1c4e8-bc05-4a24-9665-4a402a18c891``


- create employee [POST]: ``/api/employees``

  payload:
  pola `phones`, `email`, `address` nie są wymagane
```
{
    "companyUUID": "54c20316-bef8-429d-a9ae-44ddb90149a3",
    "roleUUID": "edd5248d-c72c-49e4-97a2-134424d9edda",
    "firstName": "Jan",
    "lastName": "Adamski",
    "email": "jan.adamski@example.com",
    "phones": [
        "444-444-111",
        "444-444-222",
        "444-444-333"
    ],
    "address": {
        "street": "Nowakowska 1ww",
        "postcode": "11-111",
        "city": "Gdańsk",
        "country": "Polska"
    }
}
```

  Nowy employee może się logować danymi - login i hasło to jego email.
  Nie ma ustawionych żadnych dostępów / uprawnień.

- update employee [PUT]: ``/api/employees/00c1c4e8-bc05-4a24-9665-4a402a18c891``

  payload:  
    pola `phones`, `emails`, `address` nie są wymagane 
```
{
    "uuid": "00c1c4e8-bc05-4a24-9665-4a402a18c891",
    "companyUUID": "3e005155-b1ec-4ff6-9455-87c76a1f99dd",
    "roleUUID": "776f8cf0-8b7d-4fb3-aa8a-2b8836403d48",
    "firstName": "Jan",
    "lastName": "Zieliński",
    "email": "jan.zielinskiii@example.com",
    "phones": [
        "444-444-111",
        "444-444-222",
        "444-444-333"
    ],
    "address": {
        "street": "Nowakowska 1ww",
        "postcode": "11-111",
        "city": "Gdańsk",
        "country": "Polska"
    }
}
```