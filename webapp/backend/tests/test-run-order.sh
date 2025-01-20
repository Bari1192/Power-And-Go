## Összesítésként egyben ##
docker compose exec backend php artisan test

### Sorrendben, teszt-osztályokban ###
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
docker compose exec backend php artisan test --filter=EquipmentDatabaseTest
docker compose exec backend php artisan test --filter=EquipmentModelTest
docker compose exec backend php artisan test --filter=CarIntegrationTest

