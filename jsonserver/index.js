const bodyParser = require('body-parser');
const jsonServer = require('json-server');
const multer = require('multer');
const fs = require('fs');
const path = require('path');

const forms = multer();
const server = jsonServer.create();
const middlewares = jsonServer.defaults();

server.use(middlewares);
server.use(jsonServer.bodyParser);
server.use(forms.array());
server.use(bodyParser.urlencoded({ extended: true }));

// Dinamikus útvonal osztálya
class RouteManager {
    constructor(server, routesConfig) {
        this.server = server;
        this.routesConfig = routesConfig;
    }

    loadData(filePath) {
        const fullPath = path.join(__dirname, filePath);
        const rawData = fs.readFileSync(fullPath, 'utf-8');
        return JSON.parse(rawData); 
    }

    registerRoutes() {
        for (const [route, config] of Object.entries(this.routesConfig)) {
            this.server.get(route, (req, res) => {
                const data = this.loadData(config.file);
                res.json(data[config.key]);
            });
        }
    }
}

// Útvonalak Configja
const routesConfig = {
    '/cars': { file: './data/autok.json', key: 'cars' },
    '/vehicle_specs': { file: './data/felszereltseg.json', key: 'vehicle_specs' },
    '/car_category': { file: './data/kategoriak.json', key: 'car_category' },
    '/users': { file: './data/felhasznalok.json', key: 'users' },
    '/personal_datas': { file: './data/szemelyek.json', key: 'personal_datas' },
    '/rental_history': { file: './data/lezart_berlesek.json', key: 'rental_history' },
};

// RouteManager & útvonal reg.
const routeManager = new RouteManager(server, routesConfig);
routeManager.registerRoutes();

// Szerver indítása
server.listen(3000, () => {
    console.log('JSON Server is running with dynamically registered routes');
});
