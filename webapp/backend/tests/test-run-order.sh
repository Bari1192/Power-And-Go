
### Sorrendben | [teszt] | Controller osztályokban ###
docker compose exec backend php artisan test --filter=Step1_FleetControllerTest
docker compose exec backend php artisan test --filter=Step2_CategoryControllerTest
docker compose exec backend php artisan test --filter=Step3_SubscriptionControllerTest
docker compose exec backend php artisan test --filter=Step4_CarStatusControllerTest
docker compose exec backend php artisan test --filter=Step5_CarControllerTest
docker compose exec backend php artisan test --filter=Step6_PersonControllerTest
docker compose exec backend php artisan test --filter=Step7_UserControllerTest
docker compose exec backend php artisan test --filter=Step8_EmployeeControllerTest
docker compose exec backend php artisan test --filter=Step9_BillControllerTest
docker compose exec backend php artisan test --filter=Step10_TicketControllerTest
docker compose exec backend php artisan test --filter=Step11_RegisterControllerTest
docker compose exec backend php artisan test --filter=Step12_GoogleApiControllerTest

### Sorrendben | [teszt] | Adatbázis osztályokban ###
docker compose exec backend php artisan test --filter=Step1_Fleets_DatabaseTest
docker compose exec backend php artisan test --filter=Step2_Categories_DatabaseTest
docker compose exec backend php artisan test --filter=Step3_Subscription_DatabaseTest
docker compose exec backend php artisan test --filter=Step4_CarStatus_DatabaseTest
docker compose exec backend php artisan test --filter=Step5_Cars_DatabaseTest
docker compose exec backend php artisan test --filter=Step6_Persons_DatabaseTest
docker compose exec backend php artisan test --filter=Step7_Users_DatabaseTest
docker compose exec backend php artisan test --filter=Step8_Employees_DatabaseTest
docker compose exec backend php artisan test --filter=Step9_Bills_DatabaseTest
docker compose exec backend php artisan test --filter=Step10_Tickets_DatabaseTest 
docker compose exec backend php artisan test --filter=Step11_Equipment_DatabaseTest 
docker compose exec backend php artisan test --filter=Step12_Prices_DatabaseTest
docker compose exec backend php artisan test --filter=Step13_DailyRentals_DatabaseTest

docker compose exec backend php artisan test --filter=EquipmentModelTest

### Sorrendben | [teszt] | [Modul-Functions] osztályokban ###
docker compose exec backend php artisan test --filter=ChargeFactoryTest
docker compose exec backend php artisan test --filter=ParkingFactoryTest

## Összesítésként egyben ##
docker compose exec backend php artisan test



