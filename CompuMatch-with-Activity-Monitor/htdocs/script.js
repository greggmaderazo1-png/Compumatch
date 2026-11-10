// Extra memory slots 'ram2'..'ram24' (see RAM_EXTRA_SLOTS), all empty.
function emptyRamExtras() {
    const o = {};
    for (let i = 2; i <= 24; i++) o['ram' + i] = null;
    return o;
}

// =====================
// Component Database
// =====================
const componentsDB = {
    cpus: [
        { id: 1, name: "Core i3-13100", price: 8500, socket: "LGA1700", cores: 4, tier: "entry", tdp: 60, brand: "intel", brandName: "Intel", generation: 13 },
        { id: 2, name: "Core i5-13400", price: 13500, socket: "LGA1700", cores: 10, tier: "balanced", tdp: 65, brand: "intel", brandName: "Intel", generation: 13 },
        { id: 3, name: "Core i7-13700K", price: 22500, socket: "LGA1700", cores: 16, tier: "high", tdp: 125, brand: "intel", brandName: "Intel", generation: 13 },
        { id: 4, name: "Core i9-13900K", price: 32500, socket: "LGA1700", cores: 24, tier: "enthusiast", tdp: 125, brand: "intel", brandName: "Intel", generation: 13 },
        { id: 5, name: "Ryzen 5 5600X", price: 9500, socket: "AM4", cores: 6, tier: "entry", tdp: 65, brand: "amd", brandName: "AMD", generation: 5 },
        { id: 6, name: "Ryzen 5 7600X", price: 14500, socket: "AM5", cores: 6, tier: "balanced", tdp: 105, brand: "amd", brandName: "AMD", generation: 7 },
        { id: 7, name: "Ryzen 7 7800X3D", price: 26500, socket: "AM5", cores: 8, tier: "enthusiast", tdp: 120, brand: "amd", brandName: "AMD", generation: 7 },
        { id: 8, name: "Ryzen 9 7950X", price: 38500, socket: "AM5", cores: 16, tier: "enthusiast", tdp: 170, brand: "amd", brandName: "AMD", generation: 7 }
    ],
    gpus: [
        { id: 1, name: "GTX 1650", price: 9500, tier: "entry", power: 300, vram: "4GB", brand: "nvidia", brandName: "NVIDIA" },
        { id: 2, name: "GTX 1660 Super", price: 12500, tier: "entry", power: 350, vram: "6GB", brand: "nvidia", brandName: "NVIDIA" },
        { id: 3, name: "RTX 3060", price: 18500, tier: "balanced", power: 450, vram: "12GB", brand: "nvidia", brandName: "NVIDIA" },
        { id: 4, name: "RTX 3060 Ti", price: 22500, tier: "balanced", power: 500, vram: "8GB", brand: "nvidia", brandName: "NVIDIA" },
        { id: 5, name: "RTX 4060", price: 21500, tier: "balanced", power: 450, vram: "8GB", brand: "nvidia", brandName: "NVIDIA" },
        { id: 6, name: "RTX 4060 Ti", price: 28500, tier: "high", power: 500, vram: "8GB", brand: "nvidia", brandName: "NVIDIA" },
        { id: 7, name: "RTX 4070", price: 38500, tier: "high", power: 550, vram: "12GB", brand: "nvidia", brandName: "NVIDIA" },
        { id: 8, name: "RTX 4070 Ti", price: 52500, tier: "enthusiast", power: 650, vram: "12GB", brand: "nvidia", brandName: "NVIDIA" },
        { id: 9, name: "RTX 4080", price: 68500, tier: "enthusiast", power: 750, vram: "16GB", brand: "nvidia", brandName: "NVIDIA" },
        { id: 10, name: "RX 6600", price: 16500, tier: "balanced", power: 400, vram: "8GB", brand: "amd", brandName: "AMD" },
        { id: 11, name: "RX 6700 XT", price: 24500, tier: "high", power: 500, vram: "12GB", brand: "amd", brandName: "AMD" },
        { id: 12, name: "RX 6800 XT", price: 39500, tier: "enthusiast", power: 650, vram: "16GB", brand: "amd", brandName: "AMD" }
    ],
    motherboards: [
        { id: 1, name: "MSI PRO B660M-A", price: 6500, socket: "LGA1700", form: "mATX", chipset: "B660", ramType: "DDR4", maxRam: 64, brand: "msi", brandName: "MSI" },
        { id: 2, name: "MSI PRO B760M-A", price: 7500, socket: "LGA1700", form: "mATX", chipset: "B760", ramType: "DDR5", maxRam: 128, brand: "msi", brandName: "MSI" },
        { id: 3, name: "ASUS TUF B650-PLUS", price: 12500, socket: "AM5", form: "ATX", chipset: "B650", ramType: "DDR5", maxRam: 128, brand: "asus", brandName: "ASUS" },
        { id: 4, name: "ASUS ROG STRIX Z790", price: 18500, socket: "LGA1700", form: "ATX", chipset: "Z790", ramType: "DDR5", maxRam: 128, brand: "asus", brandName: "ASUS" },
        { id: 5, name: "Gigabyte B760 DS3H", price: 8500, socket: "LGA1700", form: "ATX", chipset: "B760", ramType: "DDR5", maxRam: 128, brand: "gigabyte", brandName: "Gigabyte" },
        { id: 6, name: "ASRock B650M Pro RS", price: 9500, socket: "AM5", form: "mATX", chipset: "B650", ramType: "DDR5", maxRam: 128, brand: "asus", brandName: "ASUS" },
        { id: 7, name: "MSI MAG B550 Tomahawk", price: 10500, socket: "AM4", form: "ATX", chipset: "B550", ramType: "DDR4", maxRam: 128, brand: "msi", brandName: "MSI" },
        { id: 8, name: "Gigabyte A620M", price: 5500, socket: "AM5", form: "mATX", chipset: "A620", ramType: "DDR5", maxRam: 96, brand: "gigabyte", brandName: "Gigabyte" }
    ],
    rams: [
        { id: 1, name: "Corsair Vengeance 8GB DDR4", price: 1800, type: "DDR4", capacity: 8, speed: 3200, brand: "corsair", brandName: "Corsair" },
        { id: 2, name: "Corsair Vengeance 16GB DDR4", price: 3200, type: "DDR4", capacity: 16, speed: 3200, brand: "corsair", brandName: "Corsair" },
        { id: 3, name: "Corsair Vengeance 32GB DDR4", price: 5800, type: "DDR4", capacity: 32, speed: 3600, brand: "corsair", brandName: "Corsair" },
        { id: 4, name: "Kingston Fury 16GB DDR5", price: 4500, type: "DDR5", capacity: 16, speed: 5200, brand: "kingston", brandName: "Kingston" },
        { id: 5, name: "Kingston Fury 32GB DDR5", price: 7800, type: "DDR5", capacity: 32, speed: 5600, brand: "kingston", brandName: "Kingston" },
        { id: 6, name: "G.Skill Trident 16GB DDR5", price: 5500, type: "DDR5", capacity: 16, speed: 6000, brand: "gskill", brandName: "G.Skill" },
        { id: 7, name: "G.Skill Trident 32GB DDR5", price: 8900, type: "DDR5", capacity: 32, speed: 6000, brand: "gskill", brandName: "G.Skill" },
        { id: 8, name: "Corsair Vengeance 16GB DDR5", price: 5200, type: "DDR5", capacity: 16, speed: 5600, brand: "corsair", brandName: "Corsair" },
        { id: 9, name: "Corsair Vengeance 32GB DDR5", price: 8900, type: "DDR5", capacity: 32, speed: 6000, brand: "corsair", brandName: "Corsair" }
    ],
    storages: [
        { id: 1, name: "Samsung 980 500GB NVMe", price: 2800, type: "SSD", capacity: "500GB", interface: "NVMe", brand: "samsung", brandName: "Samsung" },
        { id: 2, name: "Samsung 980 1TB NVMe", price: 4800, type: "SSD", capacity: "1TB", interface: "NVMe", brand: "samsung", brandName: "Samsung" },
        { id: 3, name: "WD Black 1TB NVMe", price: 5500, type: "SSD", capacity: "1TB", interface: "NVMe", brand: "wd", brandName: "WD" },
        { id: 4, name: "Kingston 2TB NVMe", price: 8500, type: "SSD", capacity: "2TB", interface: "NVMe", brand: "kingston", brandName: "Kingston" },
        { id: 5, name: "Seagate Barracuda 1TB HDD", price: 2500, type: "HDD", capacity: "1TB", interface: "SATA", brand: "seagate", brandName: "Seagate" },
        { id: 6, name: "Seagate Barracuda 2TB HDD", price: 3500, type: "HDD", capacity: "2TB", interface: "SATA", brand: "seagate", brandName: "Seagate" },
        { id: 7, name: "Corsair MP600 1TB NVMe", price: 6200, type: "SSD", capacity: "1TB", interface: "NVMe", brand: "corsair", brandName: "Corsair" },
        { id: 8, name: "Samsung 990 Pro 1TB NVMe", price: 8500, type: "SSD", capacity: "1TB", interface: "NVMe", brand: "samsung", brandName: "Samsung" }
    ],
    psus: [
        { id: 1, name: "Corsair CV550 550W", price: 3200, wattage: 550, rating: "Bronze", efficiency: 82, brand: "corsair", brandName: "Corsair" },
        { id: 2, name: "Corsair CV650 650W", price: 4200, wattage: 650, rating: "Bronze", efficiency: 82, brand: "corsair", brandName: "Corsair" },
        { id: 3, name: "Cooler Master MWE 650W", price: 4500, wattage: 650, rating: "Gold", efficiency: 90, brand: "coolermaster", brandName: "Cooler Master" },
        { id: 4, name: "Cooler Master MWE 750W", price: 5500, wattage: 750, rating: "Gold", efficiency: 90, brand: "coolermaster", brandName: "Cooler Master" },
        { id: 5, name: "Seasonic Focus 750W", price: 6800, wattage: 750, rating: "Gold", efficiency: 90, brand: "seasonic", brandName: "Seasonic" },
        { id: 6, name: "Seasonic Focus 850W", price: 8200, wattage: 850, rating: "Gold", efficiency: 90, brand: "seasonic", brandName: "Seasonic" },
        { id: 7, name: "Corsair RM850e 850W", price: 9500, wattage: 850, rating: "Gold", efficiency: 90, brand: "corsair", brandName: "Corsair" },
        { id: 8, name: "Corsair RM750e 750W", price: 7500, wattage: 750, rating: "Gold", efficiency: 90, brand: "corsair", brandName: "Corsair" },
        { id: 9, name: "Cooler Master MWE 850W", price: 7200, wattage: 850, rating: "Gold", efficiency: 90, brand: "coolermaster", brandName: "Cooler Master" }
    ],
    cases: [
        { id: 1, name: "MasterBox Q300L", price: 2800, form: "mATX", color: "Black", fans: 1, brand: "coolermaster", brandName: "Cooler Master" },
        { id: 2, name: "NZXT H5 Flow", price: 5500, form: "ATX", color: "Black", fans: 2, brand: "nzxt", brandName: "NZXT" },
        { id: 3, name: "NZXT H7 Flow", price: 7500, form: "ATX", color: "White", fans: 3, brand: "nzxt", brandName: "NZXT" },
        { id: 4, name: "Corsair 4000D Airflow", price: 5200, form: "ATX", color: "Black", fans: 2, brand: "corsair", brandName: "Corsair" },
        { id: 5, name: "Lian Li Lancool 216", price: 6500, form: "ATX", color: "Black", fans: 3, brand: "lianli", brandName: "Lian Li" },
        { id: 6, name: "Lian Li O11 Dynamic", price: 8500, form: "ATX", color: "White", fans: 0, brand: "lianli", brandName: "Lian Li" },
        { id: 7, name: "Cougar MX330-G", price: 2500, form: "ATX", color: "Black", fans: 1, brand: "cougar", brandName: "Cougar" },
        { id: 8, name: "Tecware Forge M2", price: 1900, form: "mATX", color: "Black", fans: 3, brand: "tecware", brandName: "Tecware" }
    ]
};

// =====================
// Store Data Integration
// =====================
let storesData = JSON.parse(localStorage.getItem('compumatch_stores') || '{}');
let selectedStore = null; // Changed from 'all' to null - user must select a store
let selectedBuildStore = null; // For build recommendations store selection
let currentBrandFilter = 'all';

// Extra inventory for PC Hub — a fuller, internally-compatible catalog
// (Intel LGA1700 + AMD AM5/AM4, DDR4/DDR5, varied GPUs/RAM/storage/PSU/cases)
const PCHUB_EXTRA_PARTS = [
    // CPUs
    { id: 'ph_cpu_i3', category: 'cpu', name: 'Intel Core i3-13100', brand: 'Intel', price: 8500, stock: 12, lowStock: 5, specs: '4 cores, LGA1700', sold: 0 },
    { id: 'ph_cpu_i7', category: 'cpu', name: 'Intel Core i7-13700K', brand: 'Intel', price: 22500, stock: 5, lowStock: 3, specs: '16 cores, LGA1700', sold: 0 },
    { id: 'ph_cpu_r7', category: 'cpu', name: 'AMD Ryzen 7 7700X', brand: 'AMD', price: 19500, stock: 6, lowStock: 3, specs: '8 cores, AM5', sold: 0 },
    { id: 'ph_cpu_r5_am4', category: 'cpu', name: 'AMD Ryzen 5 5600', brand: 'AMD', price: 7200, stock: 9, lowStock: 4, specs: '6 cores, AM4', sold: 0 },
    // Motherboards (covers every CPU above)
    { id: 'ph_mb_b760atx', category: 'motherboard', name: 'Gigabyte B760 AORUS Elite', brand: 'Gigabyte', price: 11500, stock: 5, lowStock: 3, specs: 'LGA1700, DDR5, ATX', sold: 0 },
    { id: 'ph_mb_h610', category: 'motherboard', name: 'ASRock H610M-HDV', brand: 'ASRock', price: 4800, stock: 8, lowStock: 4, specs: 'LGA1700, DDR4, mATX', sold: 0 },
    { id: 'ph_mb_b650', category: 'motherboard', name: 'ASUS TUF Gaming B650-PLUS', brand: 'ASUS', price: 11000, stock: 5, lowStock: 3, specs: 'AM5, DDR5, ATX', sold: 0 },
    { id: 'ph_mb_b550', category: 'motherboard', name: 'MSI MAG B550 Tomahawk', brand: 'MSI', price: 7800, stock: 6, lowStock: 3, specs: 'AM4, DDR4, ATX', sold: 0 },
    // GPUs
    { id: 'ph_gpu_4070', category: 'gpu', name: 'NVIDIA RTX 4070', brand: 'NVIDIA', price: 38000, stock: 4, lowStock: 2, specs: '12GB GDDR6X', sold: 0 },
    { id: 'ph_gpu_4090', category: 'gpu', name: 'NVIDIA RTX 4090', brand: 'NVIDIA', price: 115000, stock: 2, lowStock: 1, specs: '24GB GDDR6X', sold: 0 },
    { id: 'ph_gpu_7600', category: 'gpu', name: 'AMD RX 7600', brand: 'AMD', price: 18500, stock: 6, lowStock: 3, specs: '8GB GDDR6', sold: 0 },
    { id: 'ph_gpu_1660', category: 'gpu', name: 'NVIDIA GTX 1660 Super', brand: 'NVIDIA', price: 9500, stock: 7, lowStock: 3, specs: '6GB GDDR6', sold: 0 },
    // RAM (DDR4 + DDR5 + white RGB + 64GB)
    { id: 'ph_ram_ddr4', category: 'ram', name: 'Kingston Fury Beast 16GB DDR4', brand: 'Kingston', price: 2600, stock: 14, lowStock: 5, specs: '16GB, 3200MHz DDR4', sold: 0 },
    { id: 'ph_ram_ddr5w', category: 'ram', name: 'G.Skill Trident Z5 32GB DDR5 White', brand: 'G.Skill', price: 9500, stock: 7, lowStock: 3, specs: '32GB, 6000MHz DDR5 RGB White', sold: 0 },
    { id: 'ph_ram_64', category: 'ram', name: 'Corsair Vengeance 64GB DDR5', brand: 'Corsair', price: 14500, stock: 5, lowStock: 2, specs: '64GB (4x16), 5600MHz DDR5', sold: 0 },
    // Storage (SATA SSD, HDD, bigger NVMe)
    { id: 'ph_sata', category: 'storage', name: 'Crucial MX500 1TB SATA SSD', brand: 'Crucial', price: 4200, stock: 11, lowStock: 4, specs: '2.5" SATA SSD', sold: 0 },
    { id: 'ph_hdd', category: 'storage', name: 'Seagate Barracuda 2TB HDD', brand: 'Seagate', price: 3200, stock: 10, lowStock: 4, specs: '7200RPM Hard Drive', sold: 0 },
    { id: 'ph_nvme2', category: 'storage', name: 'Kingston NV2 2TB NVMe', brand: 'Kingston', price: 7500, stock: 8, lowStock: 3, specs: 'NVMe PCIe 4.0', sold: 0 },
    // PSU (650 / 850 / 1000W)
    { id: 'ph_psu650', category: 'psu', name: 'Cooler Master MWE 650W', brand: 'Cooler Master', price: 3900, stock: 9, lowStock: 4, specs: '650W Bronze', sold: 0 },
    { id: 'ph_psu850', category: 'psu', name: 'Corsair RM850e', brand: 'Corsair', price: 9500, stock: 5, lowStock: 2, specs: '850W Gold', sold: 0 },
    { id: 'ph_psu1000', category: 'psu', name: 'Seasonic Prime 1000W', brand: 'Seasonic', price: 14000, stock: 3, lowStock: 2, specs: '1000W Platinum', sold: 0 },
    // Cases (black ATX, white ATX, mini-ITX)
    { id: 'ph_case_black', category: 'case', name: 'Corsair 4000D Airflow Black', brand: 'Corsair', price: 5200, stock: 6, lowStock: 3, specs: 'ATX Mid-Tower, 2 fans', sold: 0 },
    { id: 'ph_case_white', category: 'case', name: 'Lian Li Lancool 216 White', brand: 'Lian Li', price: 6800, stock: 5, lowStock: 2, specs: 'ATX Mid-Tower, 3 fans', sold: 0 },
    { id: 'ph_case_itx', category: 'case', name: 'Cooler Master NR200', brand: 'Cooler Master', price: 4500, stock: 6, lowStock: 3, specs: 'Mini-ITX', sold: 0 }
];

// Initialize demo stores if empty
function initializeDemoStores() {
    if (Object.keys(storesData).length === 0) {
        storesData = {
            'pchub': {
                id: 'pchub',
                name: 'PC Hub Calbayog',
                address: 'Calbayog City, Samar',
                contact: '09123456789',
                email: 'pchub@example.com',
                password: 'store123',
                inventory: [
                    { id: 'cpu1', category: 'cpu', name: 'Intel Core i5-13400', brand: 'Intel', price: 13500, stock: 10, lowStock: 5, specs: '10 cores, up to 4.6GHz', sold: 0 },
                    { id: 'cpu2', category: 'cpu', name: 'AMD Ryzen 5 7600X', brand: 'AMD', price: 14500, stock: 8, lowStock: 5, specs: '6 cores, up to 5.3GHz', sold: 0 },
                    { id: 'gpu1', category: 'gpu', name: 'NVIDIA RTX 4060', brand: 'NVIDIA', price: 21500, stock: 5, lowStock: 3, specs: '8GB GDDR6', sold: 0 },
                    { id: 'gpu2', category: 'gpu', name: 'AMD RX 6700 XT', brand: 'AMD', price: 24500, stock: 3, lowStock: 3, specs: '12GB GDDR6', sold: 0 },
                    { id: 'mb1', category: 'motherboard', name: 'MSI PRO B760M-A', brand: 'MSI', price: 7500, stock: 7, lowStock: 3, specs: 'LGA1700, DDR5', sold: 0 },
                    { id: 'ram1', category: 'ram', name: 'Corsair Vengeance 32GB DDR5', brand: 'Corsair', price: 8900, stock: 12, lowStock: 5, specs: '32GB, 6000MHz', sold: 0 },
                    { id: 'storage1', category: 'storage', name: 'Samsung 980 Pro 1TB NVMe', brand: 'Samsung', price: 6800, stock: 15, lowStock: 5, specs: 'NVMe PCIe 4.0', sold: 0 },
                    { id: 'psu1', category: 'psu', name: 'Corsair RM750e', brand: 'Corsair', price: 7500, stock: 6, lowStock: 3, specs: '750W Gold', sold: 0 },
                    { id: 'case1', category: 'case', name: 'NZXT H5 Flow', brand: 'NZXT', price: 5500, stock: 6, lowStock: 3, specs: 'ATX Mid-Tower, 2 fans', sold: 0 },
                    { id: 'case2', category: 'case', name: 'Cooler Master Q300L', brand: 'Cooler Master', price: 2800, stock: 9, lowStock: 4, specs: 'mATX, 1 fan', sold: 0 },
                    ...PCHUB_EXTRA_PARTS
                ],
                orders: []
            },
            'techhaven': {
                id: 'techhaven',
                name: 'Tech Haven',
                address: 'Calbayog City, Samar',
                contact: '09876543210',
                email: 'techhaven@example.com',
                password: 'store123',
                inventory: [
                    { id: 'cpu3', category: 'cpu', name: 'Intel Core i7-13700K', brand: 'Intel', price: 22500, stock: 4, lowStock: 3, specs: '16 cores, up to 5.4GHz', sold: 0 },
                    { id: 'gpu3', category: 'gpu', name: 'NVIDIA RTX 4070', brand: 'NVIDIA', price: 38500, stock: 3, lowStock: 2, specs: '12GB GDDR6X', sold: 0 },
                    { id: 'ram2', category: 'ram', name: 'G.Skill Trident 32GB DDR5', brand: 'G.Skill', price: 8900, stock: 8, lowStock: 3, specs: '32GB, 6000MHz RGB', sold: 0 },
                    { id: 'storage2', category: 'storage', name: 'WD Black 1TB NVMe', brand: 'WD', price: 5500, stock: 10, lowStock: 3, specs: 'NVMe PCIe 4.0', sold: 0 },
                    { id: 'psu2', category: 'psu', name: 'Seasonic Focus 750W', brand: 'Seasonic', price: 6800, stock: 5, lowStock: 2, specs: '750W Gold', sold: 0 },
                    { id: 'case3', category: 'case', name: 'Lian Li Lancool 216', brand: 'Lian Li', price: 6500, stock: 4, lowStock: 2, specs: 'ATX Mid-Tower, 3 fans', sold: 0 },
                    { id: 'case4', category: 'case', name: 'Corsair 4000D Airflow', brand: 'Corsair', price: 5200, stock: 5, lowStock: 2, specs: 'ATX Mid-Tower, 2 fans', sold: 0 }
                ],
                orders: []
            }
        };
        localStorage.setItem('compumatch_stores', JSON.stringify(storesData));
    }
}

// Migration: make sure the two demo stores carry PC cases even if they were
// seeded by an older version that didn't include them.
function ensureDemoCases() {
    let changed = false;
    const demoCases = {
        pchub: [
            { id: 'case1', category: 'case', name: 'NZXT H5 Flow', brand: 'NZXT', price: 5500, stock: 6, lowStock: 3, specs: 'ATX Mid-Tower, 2 fans', sold: 0 },
            { id: 'case2', category: 'case', name: 'Cooler Master Q300L', brand: 'Cooler Master', price: 2800, stock: 9, lowStock: 4, specs: 'mATX, 1 fan', sold: 0 }
        ],
        techhaven: [
            { id: 'case3', category: 'case', name: 'Lian Li Lancool 216', brand: 'Lian Li', price: 6500, stock: 4, lowStock: 2, specs: 'ATX Mid-Tower, 3 fans', sold: 0 },
            { id: 'case4', category: 'case', name: 'Corsair 4000D Airflow', brand: 'Corsair', price: 5200, stock: 5, lowStock: 2, specs: 'ATX Mid-Tower, 2 fans', sold: 0 }
        ]
    };
    for (const storeId in demoCases) {
        const store = storesData[storeId];
        if (store && Array.isArray(store.inventory) && !store.inventory.some(i => i.category === 'case')) {
            store.inventory.push(...demoCases[storeId]);
            changed = true;
        }
    }
    if (changed) localStorage.setItem('compumatch_stores', JSON.stringify(storesData));
}

// Migration: add the expanded PC Hub catalog to existing installs — runs once,
// so later admin edits/deletions of these items are respected.
function ensurePcHubExtras() {
    if (localStorage.getItem('compumatch_pchub_extras') === '1') return;
    const store = storesData['pchub'];
    if (store && Array.isArray(store.inventory)) {
        PCHUB_EXTRA_PARTS.forEach(p => {
            if (!store.inventory.some(i => i.id === p.id)) store.inventory.push({ ...p });
        });
        localStorage.setItem('compumatch_stores', JSON.stringify(storesData));
    }
    localStorage.setItem('compumatch_pchub_extras', '1');
}

// Get all stores list
function getAllStores() {
    const stores = [];
    for (const storeId in storesData) {
        // Hide stores whose business permit hasn't been approved yet.
        // (Strict === false so older/localStorage-only stores without the flag still show.)
        if (storesData[storeId].verified === false) continue;
        stores.push({
            id: storeId,
            name: storesData[storeId].name,
            address: storesData[storeId].address,
            latitude: storesData[storeId].latitude,
            longitude: storesData[storeId].longitude,
            itemCount: storesData[storeId].inventory.length
        });
    }
    return stores;
}

// Google Maps link for a store's pinned location ('' if the store has no pin).
function storeMapUrl(store) {
    if (!store || store.latitude == null || store.longitude == null) return '';
    return 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(store.latitude + ',' + store.longitude);
}

// Render store selection grids
function renderStoreSelection() {
    const stores = getAllStores();
    
    // Home page stores — cards look great with a handful of stores, but
    // get cramped once many partners sign up, so switch to a dropdown
    // past a threshold (mirrors the same pattern used for customStoreGrid).
    const homeStoreGrid = document.getElementById('homeStoreGrid');
    const HOME_STORE_CARD_LIMIT = 6;
    if (homeStoreGrid) {
        if (stores.length > HOME_STORE_CARD_LIMIT) {
            homeStoreGrid.innerHTML = `
                <select class="store-select" onchange="if(this.value) selectStoreAndGoToBuild(this.value)" style="width:100%;max-width:420px;margin:0 auto;display:block;padding:12px 16px;border-radius:10px;border:1px solid var(--border,#2d2d3a);background:var(--card,#1a1a24);color:var(--text,#e8e8ef);font-size:0.95rem;font-family:inherit;cursor:pointer;">
                    <option value="">Choose a partner store…</option>
                    ${stores.map(store => `
                        <option value="${escapeHtml(store.id)}">${escapeHtml(store.name)} — ${store.itemCount} items available</option>
                    `).join('')}
                </select>
            `;
        } else {
            homeStoreGrid.innerHTML = stores.map(store => `
                <div class="store-card" onclick="selectStoreAndGoToBuild('${store.id}')">
                    <i class="fas fa-store"></i>
                    <h4>${escapeHtml(store.name)}</h4>
                    <p>${store.itemCount} items available</p>
                    ${storeMapUrl(store) ? `<a href="${storeMapUrl(store)}" target="_blank" rel="noopener" onclick="event.stopPropagation()" style="display:inline-block;margin-top:6px;font-size:0.8rem;color:var(--accent,#00D4FF);text-decoration:none;"><i class="fas fa-map-location-dot"></i> View on map</a>` : ''}
                </div>
            `).join('');
        }
    }
    
    // Build page stores — same card-to-dropdown threshold as the home page
    const buildStoreGrid = document.getElementById('buildStoreGrid');
    if (buildStoreGrid) {
        if (stores.length > HOME_STORE_CARD_LIMIT) {
            buildStoreGrid.innerHTML = `
                <select class="store-select" onchange="if(this.value) selectBuildStore(this.value)" style="width:100%;max-width:420px;margin:0 auto;display:block;padding:12px 16px;border-radius:10px;border:1px solid var(--border,#2d2d3a);background:var(--card,#1a1a24);color:var(--text,#e8e8ef);font-size:0.95rem;font-family:inherit;cursor:pointer;">
                    <option value="">Choose a partner store…</option>
                    ${stores.map(store => `
                        <option value="${escapeHtml(store.id)}" ${selectedBuildStore === store.id ? 'selected' : ''}>${escapeHtml(store.name)} — ${store.itemCount} items available</option>
                    `).join('')}
                </select>
            `;
        } else {
            buildStoreGrid.innerHTML = stores.map(store => `
                <div class="store-card ${selectedBuildStore === store.id ? 'selected' : ''}" onclick="selectBuildStore('${store.id}')">
                    <i class="fas fa-store"></i>
                    <h4>${escapeHtml(store.name)}</h4>
                    <p>${store.itemCount} items available</p>
                </div>
            `).join('');
        }
    }
    
    // Custom build stores — dropdown (cards get unwieldy once many stores sign up)
    const customStoreGrid = document.getElementById('customStoreGrid');
    if (customStoreGrid) {
        customStoreGrid.innerHTML = `
            <select class="store-select" onchange="selectCustomStore(this.value)" style="width:100%;padding:12px 16px;border-radius:10px;border:1px solid var(--border,#2d2d3a);background:var(--card,#1a1a24);color:var(--text,#e8e8ef);font-size:0.95rem;font-family:inherit;cursor:pointer;">
                <option value="__all__" ${selectedStore === '__all__' ? 'selected' : ''}>🌐 All Stores — mix parts from every partner store</option>
                ${stores.map(store => `
                    <option value="${escapeHtml(store.id)}" ${selectedStore === store.id ? 'selected' : ''}>${escapeHtml(store.name)} — ${store.itemCount} items available</option>
                `).join('')}
            </select>
        `;
    }
    
    // Browse page stores — dropdown (same reasoning as Custom Build above)
    const browseStoreGrid = document.getElementById('browseStoreGrid');
    if (browseStoreGrid) {
        browseStoreGrid.innerHTML = `
            <select class="store-select" onchange="selectBrowseStore(this.value || null)" style="width:100%;padding:12px 16px;border-radius:10px;border:1px solid var(--border,#2d2d3a);background:var(--card,#1a1a24);color:var(--text,#e8e8ef);font-size:0.95rem;font-family:inherit;cursor:pointer;">
                <option value="" ${selectedStore === null || selectedStore === '__all__' ? 'selected' : ''}>All Stores — compare prices</option>
                ${stores.map(store => `
                    <option value="${escapeHtml(store.id)}" ${selectedStore === store.id ? 'selected' : ''}>${escapeHtml(store.name)} — ${store.itemCount} items</option>
                `).join('')}
            </select>
        `;
    }
}

// Focuses (and, where supported, opens) a store <select> dropdown — used by
// "Change Store" buttons that used to click a .store-card before the store
// picker became a dropdown.
function focusStoreSelect(containerId) {
    const sel = document.querySelector('#' + containerId + ' select.store-select');
    if (!sel) return;
    sel.focus();
    if (typeof sel.showPicker === 'function') {
        try { sel.showPicker(); } catch (e) { /* not supported in this browser */ }
    }
}

// Store selection functions
function selectStoreAndGoToBuild(storeId) {
    selectedStore = storeId;
    showPage('custom');
    showToast(`Building with parts from: ${storesData[storeId]?.name}`);
}

function selectBuildStore(storeId) {
    selectedBuildStore = storeId;
    renderStoreSelection();
    document.getElementById('selectedStoreWarning').style.display = 'none';
    document.getElementById('buildPreferencesForm').style.display = 'block';
    showToast(`Selected store: ${storesData[storeId]?.name}`);
}

function selectCustomStore(storeId) {
    selectedStore = storeId;
    renderStoreSelection();
    document.getElementById('customSelectedStoreWarning').style.display = 'none';
    document.getElementById('customBuildContent').style.display = 'block';
    
    // Reset components when changing store
    customBuildComponents = { cpu: null, gpu: null, motherboard: null, ram: null, storage: null, storage2: null, storage3: null, storage4: null, storage5: null, storage6: null, storage7: null, storage8: null, storage9: null, nvme2: null, nvme3: null, nvme4: null, nvme5: null, nvme6: null, nvme7: null, ...emptyRamExtras(), psu: null, case: null };
    compatibilityIssues = [];
    compatChecked = false;
    
    renderComponentSelectors();
    updateSummaryPanel();
    showToast(storeId === '__all__' ? 'Now browsing: All Stores' : `Now browsing: ${storesData[storeId]?.name}`);
}

function selectBrowseStore(storeId) {
    selectedStore = storeId;
    renderStoreSelection();
    renderBrowseParts();
    if (storeId) {
        showToast(`Filtering: ${storesData[storeId]?.name}`);
    } else {
        showToast(`Showing all stores`);
    }
}

// Get items from ALL stores combined (Custom Build "All Stores" mode)
function getAllStoresItemsByCategory(category) {
    const items = [];
    for (const storeId in storesData) {
        const store = storesData[storeId];
        (store.inventory || [])
            .filter(item => item.category === category && item.stock > 0)
            .forEach(item => items.push({
                ...item,
                isStoreItem: true,
                storeName: store.name,
                storeId: store.id,
                brandName: item.brand
            }));
    }
    return items;
}

// Get items from selected store only
function getStoreItemsByCategory(category) {
    if (!selectedStore) return [];
    
    const store = storesData[selectedStore];
    if (!store) return [];
    
    return store.inventory
        .filter(item => item.category === category && item.stock > 0)
        .map(item => ({
            ...item,
            isStoreItem: true,
            storeName: store.name,
            storeId: store.id,
            brandName: item.brand
        }));
}

// Get component items (only from selected store)
// =====================
// Compatibility helpers — derive socket / RAM type / form-factor from a
// store item's name + specs (store items only carry text specs).
// =====================
// Motherboard chipset -> CPU socket, used when a board has no socket saved.
var CHIPSET_SOCKET = {};
[['AM5',     'A620 B650 X670 B840 B850 X870'],
 ['AM4',     'A320 B350 X370 B450 X470 A520 B550 X570'],
 ['LGA1700', 'H610 B660 H670 Z690 B760 H770 Z790'],
 ['LGA1200', 'H410 B460 H470 Z490 H510 B560 H570 Z590'],
 ['LGA1151', 'H110 B150 H170 Z170 B250 H270 Z270 H310 B360 B365 H370 Z370 Z390']
].forEach(function (row) { row[1].split(' ').forEach(function (c) { CHIPSET_SOCKET[c] = row[0]; }); });

function compatSocket(item) {
    if (!item) return null;
    if (item.socket) return String(item.socket).toUpperCase().replace(/\s+/g, '');   // typed by the store; any value
    var s = ((item.specs || '') + ' ' + (item.name || '')).toUpperCase();
    if (/LGA\s?1700/.test(s)) return 'LGA1700';
    if (/LGA\s?1200/.test(s)) return 'LGA1200';
    if (/LGA\s?1151/.test(s)) return 'LGA1151';
    if (/\bAM5\b/.test(s)) return 'AM5';
    if (/\bAM4\b/.test(s)) return 'AM4';
    var n = (item.name || '').toUpperCase();
    // derive from the motherboard chipset in the name (e.g. "MSI B450" -> AM4)
    if (item.category === 'motherboard') {
        var chip = n.match(/\b([ABHXZ]\d{3})[A-Z]{0,2}\b/);
        if (chip && CHIPSET_SOCKET[chip[1]]) return CHIPSET_SOCKET[chip[1]];
    }
    // derive from CPU model name
    if (/I[3579][\s-]*1[234]\d{3}/.test(n)) return 'LGA1700';      // Intel 12/13/14th gen
    if (/I[3579][\s-]*1[01]\d{3}/.test(n)) return 'LGA1200';       // Intel 10/11th gen
    if (/I[3579][\s-]*[6-9]\d{3}(?!\d)/.test(n)) return 'LGA1151'; // Intel 6th–9th gen
    if (/RYZEN\s*\d\s*(?:PRO\s*)?[789]\d{3}/.test(n)) return 'AM5'; // Ryzen 7000/8000/9000
    if (/RYZEN\s*\d\s*(?:PRO\s*)?[1-5]\d{3}/.test(n)) return 'AM4'; // Ryzen 1000–5000
    return null;
}
function compatRam(item) {
    if (!item) return null;
    if (item.ram_type) return String(item.ram_type).toUpperCase().replace(/\s+/g, '');   // typed by the store; any value
    if (item.ramType) return item.ramType;
    if (item.type && /DDR/i.test(item.type)) return item.type.toUpperCase();
    var s = ((item.specs || '') + ' ' + (item.name || '')).toUpperCase();
    if (/DDR5/.test(s)) return 'DDR5';
    if (/DDR4/.test(s)) return 'DDR4';
    // A board with no RAM type saved: its socket settles it for most platforms
    // (AM5 is DDR5-only; AM4 / LGA1200 / LGA1151 are DDR4). LGA1700 boards
    // come in both, so those stay unknown.
    if (item.category === 'motherboard') {
        var sock = compatSocket(item);
        if (sock === 'AM5') return 'DDR5';
        if (sock === 'AM4' || sock === 'LGA1200' || sock === 'LGA1151') return 'DDR4';
    }
    return null;
}
function moboForm(item) {
    if (!item) return 'ATX';
    if (item.form) return item.form.toUpperCase().replace('MICRO-ATX', 'MATX');
    var s = ((item.name || '') + ' ' + (item.specs || '')).toUpperCase();
    if (/MINI[- ]?ITX|\bITX\b/.test(s)) return 'ITX';
    if (/M[- ]?ATX|MICRO|\b[BHXZA]\d{3}M\b/.test(s)) return 'MATX';
    return 'ATX';
}
function caseForms(caseItem) {
    var s = ((caseItem.name || '') + ' ' + (caseItem.specs || '')).toUpperCase();
    if (/MID|FULL|\bATX\b/.test(s) && !/M[- ]?ATX|MICRO/.test(s)) return ['ATX', 'MATX', 'ITX'];
    if (/M[- ]?ATX|MICRO/.test(s)) return ['MATX', 'ITX'];
    if (/MINI[- ]?ITX|\bITX\b/.test(s)) return ['ITX'];
    return ['ATX', 'MATX', 'ITX'];
}
// Clear any selected part that no longer fits the rest of the build
function pruneIncompatible() {
    var cb = customBuildComponents;
    if (cb.cpu && cb.motherboard) {
        var a = compatSocket(cb.cpu), b = compatSocket(cb.motherboard);
        if (a && b && a !== b) cb.motherboard = null;
    }
    if (cb.motherboard && cb.ram) {
        var mr = compatRam(cb.motherboard), rr = compatRam(cb.ram);
        if (mr && rr && mr !== rr) cb.ram = null;
    }
    if (cb.motherboard && cb.case) {
        if (caseForms(cb.case).indexOf(moboForm(cb.motherboard)) === -1) cb.case = null;
    }
}

// Secondary storage may only be a SATA SSD or an HDD (no NVMe / M.2)
function isSecondaryStorage(item) {
    var s = ((item.name || '') + ' ' + (item.specs || '')).toUpperCase();
    if (/NVME|M\.?2/.test(s)) return false;
    return /\bHDD\b|HARD DRIVE|7200|5400|\bSATA\b|2\.5/.test(s);
}

// Secondary drives: 'storage2' (always shown) + more added with the button.
// How many is decided by the chosen motherboard's SATA ports — see maxSecondaryDrives().
const EXTRA_STORAGE_SLOTS = ['storage2', 'storage3', 'storage4', 'storage5', 'storage6', 'storage7', 'storage8', 'storage9'];
function isExtraStorageSlot(key) { return EXTRA_STORAGE_SLOTS.indexOf(key) !== -1; }

// Extra main-storage drives in the motherboard's M.2 slots (NVMe / M.2 only).
// How many is decided by the board's M.2 slots — see maxExtraM2Drives().
const PRIMARY_EXTRA_SLOTS = ['nvme2', 'nvme3', 'nvme4', 'nvme5', 'nvme6', 'nvme7'];
function isPrimaryExtraSlot(key) { return PRIMARY_EXTRA_SLOTS.indexOf(key) !== -1; }

// An NVMe / M.2 drive (goes in an M.2 slot, not a SATA port).
function isM2Storage(item) {
    if (!item) return false;
    if (String(item.storage_type || '').toUpperCase() === 'NVME') return true;
    return /NVME|M\.?2/.test(((item.name || '') + ' ' + (item.specs || '')).toUpperCase());
}

// Extra memory kits next to the main one ('ram2'..'ram24'). How many fit is
// decided by the motherboard's RAM slots — see freeRamSlots().
const RAM_EXTRA_SLOTS = Array.from({ length: 23 }, (_, i) => 'ram' + (i + 2));
function isRamExtraSlot(key) { return RAM_EXTRA_SLOTS.indexOf(key) !== -1; }

// Sticks in a kit: "2x16GB" / "(2 x 8GB)" -> 2, "(1X16GB)" or "16GB" -> 1.
function ramSticks(item) {
    const t = ((item && item.name) || '') + ' ' + ((item && item.specs) || '');
    const m = t.match(/(\d+)\s*[xX×]\s*\d+\s*GB/);
    const n = m ? parseInt(m[1], 10) : 1;
    return n > 0 && n <= 16 ? n : 1;
}

// Used when no motherboard is chosen yet, or the store didn't enter its RAM
// slots: 2 is what most budget / mATX boards have.
const DEFAULT_RAM_SLOTS = 2;

function boardRamSlots(cb) {
    const n = cb && cb.motherboard ? parseInt(cb.motherboard.ram_slots, 10) : NaN;
    return n > 0 ? n : null;
}

// Memory sticks used by the main kit + the extra kits.
function ramSticksUsed(cb) {
    return ['ram', ...RAM_EXTRA_SLOTS].reduce((n, k) => n + (cb && cb[k] ? ramSticks(cb[k]) : 0), 0);
}

function freeRamSlots(cb) {
    cb = cb || customBuildComponents;
    return Math.max(0, (boardRamSlots(cb) || DEFAULT_RAM_SLOTS) - ramSticksUsed(cb));
}

// Used when no motherboard is chosen yet, or the store didn't enter its M.2
// slots: 1 is what every board with an M.2 slot has.
const DEFAULT_M2_SLOTS = 1;

function boardM2Slots(cb) {
    const n = cb && cb.motherboard ? parseInt(cb.motherboard.m2_slots, 10) : NaN;
    return n > 0 ? n : null;
}

function mainDriveUsesM2(cb) {
    return !!(cb && cb.storage && isM2Storage(cb.storage));
}

// How many extra M.2 drives fit next to the main drive.
function maxExtraM2Drives(cb) {
    cb = cb || customBuildComponents;
    const slots = boardM2Slots(cb) || DEFAULT_M2_SLOTS;
    const free = slots - (mainDriveUsesM2(cb) ? 1 : 0);
    return Math.max(0, Math.min(PRIMARY_EXTRA_SLOTS.length, free));
}

// Used when no motherboard is chosen yet, or the store didn't enter its SATA
// ports: 4 is what most budget / mATX boards have.
const DEFAULT_SATA_PORTS = 4;

// SATA ports of the chosen motherboard, or null if unknown.
function boardSataPorts(cb) {
    const n = cb && cb.motherboard ? parseInt(cb.motherboard.sata_ports, 10) : NaN;
    return n > 0 ? n : null;
}

// A SATA SSD / HDD as the main drive also takes one SATA port.
function mainDriveUsesSata(cb) {
    return !!(cb && cb.storage && isSecondaryStorage(cb.storage));
}

// How many secondary drives fit: the board's SATA ports (minus one if the
// main drive is SATA), capped at the number of slots a build can hold.
function maxSecondaryDrives(cb) {
    cb = cb || customBuildComponents;
    const ports = boardSataPorts(cb) || DEFAULT_SATA_PORTS;
    const free = ports - (mainDriveUsesSata(cb) ? 1 : 0);
    return Math.max(0, Math.min(EXTRA_STORAGE_SLOTS.length, free));
}

function getComponentItems(category) {
    let items = [];
    // Secondary drives (storage2..storage9) pull from the storage category
    const invCategory = (isExtraStorageSlot(category) || isPrimaryExtraSlot(category)) ? 'storage'
                      : isRamExtraSlot(category) ? 'ram' : category;
    
    // Only show items from selected store, or ALL stores in "All Stores" mode
    if (selectedStore === '__all__') {
        items.push(...getAllStoresItemsByCategory(invCategory));
    } else if (selectedStore && storesData[selectedStore]) {
        const storeItems = getStoreItemsByCategory(invCategory);
        items.push(...storeItems);
    }
    
    // Apply brand filter
    if (currentBrandFilter !== 'all') {
        items = items.filter(item => item.brand?.toLowerCase() === currentBrandFilter);
    }


    // Secondary drives (storage2..storage9): keep only SATA SSD / HDD
    if (isExtraStorageSlot(category)) {
        items = items.filter(isSecondaryStorage);
    }
    // Extra main-storage drives (nvme2..nvme7): NVMe / M.2 only
    if (isPrimaryExtraSlot(category)) {
        items = items.filter(isM2Storage);
    }
    // Extra memory (ram2..ram24): same memory type as the main kit, and only
    // kits with few enough sticks for the slots still free (not counting the
    // kit being replaced in this slot).
    if (isRamExtraSlot(category)) {
        const cbx = customBuildComponents;
        const mainType = cbx.ram ? compatRam(cbx.ram) : null;
        if (mainType) items = items.filter(r => { const t = compatRam(r); return !t || t === mainType; });
        const free = freeRamSlots(cbx) + (cbx[category] ? ramSticks(cbx[category]) : 0);
        items = items.filter(r => ramSticks(r) <= free);
    }

    // Compatibility filtering based on what's already chosen
    const cb = customBuildComponents;
    if (CHECK_ON_CLICK) return items;   // show everything; the button reports problems
    // CPU <-> motherboard: the socket must be known AND match. A part whose
    // socket can't be worked out is hidden rather than assumed compatible.
    if (category === 'motherboard' && cb.cpu) {
        const sock = compatSocket(cb.cpu);
        if (sock) items = items.filter(m => compatSocket(m) === sock);
    }
    if (category === 'cpu' && cb.motherboard) {
        const msock = compatSocket(cb.motherboard);
        if (msock) items = items.filter(c => compatSocket(c) === msock);
    }
    if ((category === 'ram' || isRamExtraSlot(category)) && cb.motherboard) {
        const mram = compatRam(cb.motherboard);
        if (mram) items = items.filter(r => { const rr = compatRam(r); return !rr || rr === mram; });
    }
    if (category === 'case' && cb.motherboard) {
        const mf = moboForm(cb.motherboard);
        items = items.filter(cs => caseForms(cs).indexOf(mf) !== -1);
    }

    return items;
}

// Check if store has items in a category
function storeHasItemsInCategory(category) {
    if (selectedStore === '__all__') {
        return Object.values(storesData).some(store =>
            (store.inventory || []).some(item => item.category === category && item.stock > 0));
    }
    if (!selectedStore || !storesData[selectedStore]) return false;
    return storesData[selectedStore].inventory.some(item => item.category === category && item.stock > 0);
}

// =====================
// State
// =====================
let currentBuild = null;
let savedBuilds = JSON.parse(localStorage.getItem('compumatch_builds') || '[]');
let customBuildComponents = {
    cpu: null,
    gpu: null,
    motherboard: null,
    ram: null,
    storage: null,
    storage2: null,
    storage3: null,
    storage4: null,
    storage5: null,
    storage6: null,
    storage7: null,
    storage8: null,
    storage9: null,
    nvme2: null, nvme3: null, nvme4: null, nvme5: null, nvme6: null, nvme7: null, ...emptyRamExtras(),
    psu: null,
    case: null
};
let compatibilityIssues = [];

// How the Custom Build page handles compatibility:
//   true  = customers pick any part freely; problems are shown only after
//           they press the "Check Compatibility" button.
//   false = dropdowns hide incompatible parts as the customer picks, and the
//           report updates automatically.
const CHECK_ON_CLICK = true;
let compatChecked = false;   // has the button been pressed for the current selection?

// =====================
// Page Navigation
// =====================
let currentPage = 'home';
let pageHistory = [];

// Phone menu (☰): open / close the nav links dropdown.
function toggleMobileNav(e) {
    if (e) e.stopPropagation();
    const nav = document.querySelector('.navbar');
    if (!nav) return;
    const open = !nav.classList.contains('nav-open');
    nav.classList.toggle('nav-open', open);
    const btn = document.getElementById('navToggle');
    if (btn) {
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        btn.innerHTML = open ? '<i class="fas fa-xmark"></i>' : '<i class="fas fa-bars"></i>';
    }
}
function closeMobileNav() {
    const nav = document.querySelector('.navbar');
    if (nav && nav.classList.contains('nav-open')) toggleMobileNav();
}
// Close it when tapping outside the menu.
document.addEventListener('click', e => {
    const nav = document.querySelector('.navbar.nav-open');
    if (nav && !nav.contains(e.target)) closeMobileNav();
});

// Highlights the top-menu link for the page that's open.
// The recommendation results belong to "Build PC".
function highlightNav(page) {
    const navPage = page === 'results' ? 'build' : page;
    document.querySelectorAll('.nav-links a[data-page]').forEach(a => {
        const on = a.dataset.page === navPage;
        a.classList.toggle('active', on);
        if (on) a.setAttribute('aria-current', 'page'); else a.removeAttribute('aria-current');
    });
}

function showPage(page, opts) {
    opts = opts || {};
    // Record where we came from so goBackPage() can return to it — but not
    // when goBackPage() itself is what triggered this call (that would just
    // push the page we're leaving right back on top of the stack).
    if (!opts.isBack && page !== currentPage) {
        pageHistory.push(currentPage);
    }
    currentPage = page;
    highlightNav(page);
    closeMobileNav();

    const pages = ['home', 'build', 'results', 'custom', 'mybuilds', 'browse', 'simulator'];
    pages.forEach(p => {
        const el = document.getElementById(`${p}-page`);
        if (el) el.classList.add('hidden');
    });

    if (page === 'home') {
        document.getElementById('home-page').classList.remove('hidden');
        renderStoreSelection();
    } else if (page === 'build') {
        document.getElementById('build-page').classList.remove('hidden');
    } else if (page === 'results') {
        document.getElementById('results-page').classList.remove('hidden');
        renderBuilds();
    } else if (page === 'custom') {
        document.getElementById('custom-page').classList.remove('hidden');
        // The store dropdown opens on "All Stores", so start there instead of
        // asking the customer to pick a store before anything shows.
        if (!selectedStore) selectedStore = '__all__';
        renderStoreSelection();
        if (selectedStore) {
            document.getElementById('customSelectedStoreWarning').style.display = 'none';
            document.getElementById('customBuildContent').style.display = 'block';
            initCustomBuild();
        } else {
            document.getElementById('customSelectedStoreWarning').style.display = 'flex';
            document.getElementById('customBuildContent').style.display = 'none';
        }
    } else if (page === 'mybuilds') {
        document.getElementById('mybuilds-page').classList.remove('hidden');
        renderSavedBuilds();
        if (getClientEmail()) syncBuildsFromBackend();
    } else if (page === 'browse') {
        document.getElementById('browse-page').classList.remove('hidden');
        renderStoreSelection();
        renderBrowseParts();
    } else if (page === 'simulator') {
        document.getElementById('simulator-page').classList.remove('hidden');
        if (typeof initSimulator === 'function') initSimulator();
    }
}

// Logo/brand click — behaves like a real back button (goes to whichever
// page was showing before this one), not always straight to Home.
function goBackPage() {
    const prev = pageHistory.pop();
    showPage(prev || 'home', { isBack: true });
}

function scrollToAbout() {
    const aboutSection = document.getElementById('aboutSection');
    if (aboutSection) {
        aboutSection.scrollIntoView({ behavior: 'smooth' });
    }
}

// =====================
// PDF Download Feature
// =====================
function downloadBuildAsPDF(build, index) {
    const pdfContent = document.createElement('div');
    pdfContent.style.padding = '20px';
    pdfContent.style.fontFamily = 'Arial, sans-serif';
    pdfContent.style.backgroundColor = 'white';
    pdfContent.style.color = '#333';

    // Which store each part comes from. Older saved builds may not carry the
    // store on each part, so fall back to looking the item up by its id.
    const findStoreIdForItem = c => {
        if (!c) return null;
        if (c.storeId && storesData[c.storeId]) return c.storeId;
        if (c.id) {
            for (const sid in storesData) {
                if ((storesData[sid].inventory || []).some(i => i.id === c.id)) return sid;
            }
        }
        return null;
    };
    const partStoreName = c => {
        if (!c) return '';
        const sid = findStoreIdForItem(c);
        if (sid) return storesData[sid].name;
        if (c.storeName) return c.storeName;
        if (build.storeName && build.storeName !== 'Multiple stores') return build.storeName;
        return '';
    };
    const partStoreAddress = c => {
        const sid = findStoreIdForItem(c);
        return sid && storesData[sid].address ? storesData[sid].address : '';
    };
    const pdfSlots = [
        ['cpu', '💻 CPU'], ['gpu', '🎮 GPU'], ['motherboard', '🔌 Motherboard'], ['ram', '📀 RAM'], ...RAM_EXTRA_SLOTS.map(k => [k, '📀 RAM']),
        ['storage', '💾 Storage'], ...PRIMARY_EXTRA_SLOTS.map(k => [k, '💾 Storage']), ...EXTRA_STORAGE_SLOTS.map(k => [k, '🗃️ Secondary Storage']),
        ['psu', '⚡ Power Supply'], ['case', '🗄️ PC Case']
    ].filter(([k]) => build.components[k]);

    const storeIdsUsed = [...new Set(pdfSlots.map(([k]) => findStoreIdForItem(build.components[k])).filter(Boolean))];
    let stores = storeIdsUsed.map(id => storesData[id]).filter(Boolean);
    if (stores.length === 0) {
        const fallback = storesData[build.storeId] || storesData[selectedStore];
        if (fallback) stores = [fallback];
    }
    const store = stores[0] || null; // kept for the single-store header line below
    
    let totalPower = 0;
    if (build.components.cpu) totalPower += build.components.cpu.tdp || 65;
    if (build.components.gpu) totalPower += build.components.gpu.power || 200;
    totalPower += 50;
    
    pdfContent.innerHTML = `
        <div style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid #6C63FF; padding-bottom: 20px;">
            <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 10px;">
                <i class="fas fa-microchip" style="font-size: 40px; color: #6C63FF;"></i>
                <h1 style="color: #6C63FF; margin: 0;">CompuMatch</h1>
            </div>
            <p style="color: #666; margin: 0;">Smart PC Build Recommendation System</p>
            <p style="color: #999; font-size: 12px; margin-top: 5px;">Northwest Samar State University - Calbayog City</p>
        </div>
        
        <div style="margin-bottom: 25px;">
            <h2 style="color: #333; border-left: 4px solid #6C63FF; padding-left: 15px;">${escapeHtml(build.name)}</h2>
            <p style="color: #666; margin-top: 8px;">📅 Date Saved: ${build.savedAt || new Date().toLocaleDateString()}</p>
            ${stores.length > 1 ? `<p style="color: #666;">🏪 Stores: ${stores.map(x => escapeHtml(x.name)).join(', ')}</p>` : store ? `<p style="color: #666;">🏪 Store: ${escapeHtml(store.name)}</p>` : ''}
        </div>

        ${stores.length > 0 ? `
        <div style="margin-bottom: 25px; padding: 15px; background: #eef0ff; border-radius: 8px; border-left: 4px solid #6C63FF;">
            <h3 style="margin-top: 0; color: #333;">📍 ${stores.length > 1 ? 'Where to Bring This List (parts come from multiple stores)' : 'Where to Bring This List'}</h3>
            ${stores.map(s => `
                <div style="margin-bottom: ${stores.length > 1 ? '10px' : '0'};">
                    <p style="margin: 4px 0;"><strong>${s.name}</strong></p>
                    ${s.address ? `<p style="margin: 4px 0; color: #555;">📍 ${s.address}</p>` : ''}
                    ${storeMapUrl(s) ? `<p style="margin: 4px 0; color: #555;">🗺️ <a href="${storeMapUrl(s)}" style="color:#6C63FF;">${storeMapUrl(s)}</a></p>` : ''}
                    ${s.contact ? `<p style="margin: 4px 0; color: #555;">📞 ${s.contact}</p>` : ''}
                    ${s.email ? `<p style="margin: 4px 0; color: #555;">✉️ ${s.email}</p>` : ''}
                </div>
            `).join('')}
        </div>
        ` : ''}
        <div style="margin-bottom: 25px;">
            <h3 style="background: #f0f0f0; padding: 10px; border-radius: 5px; color: #333;">🔧 COMPONENT LIST</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr style="background: #6C63FF; color: white;">
                    <th style="padding: 10px; text-align: left;">Component</th>
                    <th style="padding: 10px; text-align: left;">Item</th>
                    <th style="padding: 10px; text-align: left;">Store</th>
                    <th style="padding: 10px; text-align: right;">Price</th>
                </tr>
                ${pdfSlots.map(([k, label]) => {
                    const c = build.components[k];
                    return `
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 8px; vertical-align: top;"><strong>${label}</strong></td>
                    <td style="padding: 8px; vertical-align: top;">${escapeHtml(ppDisplayName(c))}</td>
                    <td style="padding: 8px; vertical-align: top;">
                        <div style="color: #6C63FF; font-weight: 600;">${escapeHtml(partStoreName(c) || '—')}</div>
                        ${partStoreAddress(c) ? `<div style="color: #777; font-size: 11px; margin-top: 2px;">📍 ${escapeHtml(partStoreAddress(c))}</div>` : ''}
                    </td>
                    <td style="padding: 8px; vertical-align: top; text-align: right; white-space: nowrap;">₱${(c.price || 0).toLocaleString()}</td>
                </tr>`;
                }).join('')}
                <tr style="background: #f9f9f9; font-weight: bold;">
                    <td colspan="3" style="padding: 10px; text-align: right;"><strong>TOTAL:</strong></td>
                    <td style="padding: 10px; text-align: right; color: #6C63FF; font-size: 18px; white-space: nowrap;">₱${(build.totalPrice || 0).toLocaleString()}</td>
                </tr>
            </table>
        </div>
        
        <div style="margin-bottom: 25px; padding: 15px; background: #f5f5f5; border-radius: 8px;">
            <h3 style="margin-top: 0; color: #333;">⚡ Power & Performance</h3>
            <p><strong>Estimated Power Consumption:</strong> ~${totalPower}W</p>
            <p><strong>Recommended PSU:</strong> ${Math.ceil(totalPower * 1.2)}W or higher</p>
        </div>
        
        <div style="margin-bottom: 25px; padding: 15px; background: #e8f5e9; border-radius: 8px;">
            <h3 style="margin-top: 0; color: #2e7d32;">📋 SHOPPING LIST</h3>
            <p style="margin-bottom: 5px;">${stores.length > 1 ? 'Bring the matching part of this list to each store noted below:' : `Present this sheet to ${store ? store.name : 'your chosen store'}${store && store.address ? ` (${store.address})` : ''}:`}</p>
            <ul style="margin: 10px 0;">
                ${pdfSlots.map(([k, label]) => {
                    const c = build.components[k];
                    const where = partStoreName(c);
                    const addr = partStoreAddress(c);
                    return `<li>✓ ${label.replace(/^\S+\s/, '')}: ${escapeHtml(ppDisplayName(c))}${stores.length > 1 && where ? ` — <em>${escapeHtml(where)}${addr ? ` (${escapeHtml(addr)})` : ''}</em>` : ''}</li>`;
                }).join('')}
            </ul>
        </div>
        
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #999; font-size: 11px;">
            <p>CompuMatch - Smart PC Building Assistant</p>
            <p>Northwest Samar State University | Calbayog City</p>
            <p>Generated on: ${new Date().toLocaleString()}</p>
        </div>
    `;
    
    const opt = {
        margin: [0.5, 0.5, 0.5, 0.5],
        filename: `CompuMatch_${build.name.replace(/[^a-z0-9]/gi, '_')}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, letterRendering: true },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
    };
    
    html2pdf().set(opt).from(pdfContent).save();
    showToast(`Downloading "${build.name}" as PDF...`);
}

function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #4CAF50;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        z-index: 10000;
        animation: fadeInOut 3s ease;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Same as showToast() but styled as a warning (red/orange) and offset a bit
// higher, so it doesn't sit exactly on top of a success toast if both happen
// to fire from the same action.
function showWarningToast(message) {
    const toast = document.createElement('div');
    toast.className = 'toast-notification toast-warning';
    toast.innerHTML = `<i class="fas fa-triangle-exclamation"></i> ${message}`;
    toast.style.cssText = `
        position: fixed;
        bottom: 80px;
        right: 20px;
        max-width: 360px;
        background: #FF5252;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        z-index: 10000;
        animation: fadeInOut 4s ease;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}

// =====================
// Budget Controls
// =====================
function initBudgetControls() {
    const minSlider = document.getElementById('budgetMinSlider');
    const maxSlider = document.getElementById('budgetMaxSlider');
    const minInput  = document.getElementById('budgetMinInput');
    const maxInput  = document.getElementById('budgetMaxInput');
    const fill      = document.getElementById('rangeFill');
    const presetBtns = document.querySelectorAll('.preset-btn');

    if (!minSlider || !maxSlider) return;

    const MIN = 15000, MAX = 200000, STEP = 5000, GAP = 5000;

    function clampStep(v) {
        if (isNaN(v)) v = MIN;
        v = Math.round(v / STEP) * STEP;
        return Math.max(MIN, Math.min(MAX, v));
    }

    function updateFill() {
        const lo = parseInt(minSlider.value);
        const hi = parseInt(maxSlider.value);
        const left  = ((lo - MIN) / (MAX - MIN)) * 100;
        const right = ((hi - MIN) / (MAX - MIN)) * 100;
        if (fill) {
            fill.style.left = left + '%';
            fill.style.width = (right - left) + '%';
        }
    }

    function syncPresets() {
        const lo = parseInt(minSlider.value);
        const hi = parseInt(maxSlider.value);
        presetBtns.forEach(btn => {
            const pMin = parseInt(btn.getAttribute('data-min'));
            const pMax = parseInt(btn.getAttribute('data-max'));
            btn.classList.toggle('active', pMin === lo && pMax === hi);
        });
    }

    // source: which handle the user moved ('min' | 'max' | 'preset' | 'init')
    function setRange(lo, hi, source) {
        lo = clampStep(lo);
        hi = clampStep(hi);

        // keep the two handles at least one step apart
        if (hi - lo < GAP) {
            if (source === 'max') {
                lo = Math.max(MIN, hi - GAP);
            } else {
                hi = Math.min(MAX, lo + GAP);
            }
        }

        minSlider.value = lo;
        maxSlider.value = hi;
        minInput.value  = lo;
        maxInput.value  = hi;

        updateFill();
        syncPresets();
    }

    minSlider.addEventListener('input', () =>
        setRange(parseInt(minSlider.value), parseInt(maxSlider.value), 'min'));
    maxSlider.addEventListener('input', () =>
        setRange(parseInt(minSlider.value), parseInt(maxSlider.value), 'max'));

    minInput.addEventListener('input', () =>
        setRange(parseInt(minInput.value), parseInt(maxSlider.value), 'min'));
    maxInput.addEventListener('input', () =>
        setRange(parseInt(minSlider.value), parseInt(maxInput.value), 'max'));
    minInput.addEventListener('blur', () =>
        setRange(parseInt(minInput.value), parseInt(maxSlider.value), 'min'));
    maxInput.addEventListener('blur', () =>
        setRange(parseInt(minSlider.value), parseInt(maxInput.value), 'max'));

    presetBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            setRange(parseInt(this.getAttribute('data-min')),
                     parseInt(this.getAttribute('data-max')), 'preset');
        });
    });

    // default range: ₱50,000 – ₱100,000
    setRange(50000, 100000, 'init');
}

// =====================
// Initialize Custom Build
// =====================
function initCustomBuild() {
    initBrandFilters();
    renderComponentSelectors();
    updateSummaryPanel();
}

function initBrandFilters() {
    const brandCards = document.querySelectorAll('.brand-card');
    brandCards.forEach(card => {
        card.addEventListener('click', function() {
            const brand = this.getAttribute('data-brand');
            setBrandFilter(brand);
        });
    });
}

function setBrandFilter(brand) {
    currentBrandFilter = brand;
    
    const brandCards = document.querySelectorAll('.brand-card');
    brandCards.forEach(card => {
        const cardBrand = card.getAttribute('data-brand');
        if (cardBrand === brand) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
    });
    
    renderComponentSelectors();
    updateSummaryPanel();
}

function getBrandDisplayName(brandKey) {
    const brandNames = {
        intel: 'Intel', amd: 'AMD', nvidia: 'NVIDIA', corsair: 'Corsair',
        kingston: 'Kingston', gskill: 'G.Skill', msi: 'MSI', asus: 'ASUS',
        gigabyte: 'Gigabyte', seasonic: 'Seasonic', coolermaster: 'Cooler Master',
        wd: 'WD', samsung: 'Samsung', seagate: 'Seagate'
    };
    return brandNames[brandKey] || brandKey;
}

// Turns raw stock/lowStock numbers into a clear, color-coded availability label.
function stockStatus(item) {
    const stock = typeof item.stock === 'number' ? item.stock : null;
    if (stock === null) return { label: '', cssClass: '', dot: '' };
    const low = item.lowStock || 5;
    if (stock <= 0) return { label: 'Out of Stock', cssClass: 'stock-out', dot: '🔴' };
    if (stock <= low) return { label: `Low Stock — only ${stock} left`, cssClass: 'stock-low', dot: '🟡' };
    return { label: `In Stock (${stock} available)`, cssClass: 'stock-in', dot: '🟢' };
}

function renderComponentSelectors() {
    const container = document.getElementById('componentsList');
    if (!container) return;

    const componentTypes = [
        { key: 'cpu', label: '💻 CPU / Processor', items: getComponentItems('cpu') },
        { key: 'motherboard', label: '🔌 Motherboard', items: getComponentItems('motherboard') },
        { key: 'ram', label: '📀 Memory (RAM)', items: getComponentItems('ram') },
        // Extra memory kits only get a row once picked (see "Add Additional Memory")
        ...RAM_EXTRA_SLOTS.filter(k => customBuildComponents[k]).map(k => ({ key: k, label: '📀 Memory', items: getComponentItems(k) })),
        { key: 'storage', label: '💾 Storage (Primary / C:)', items: getComponentItems('storage') },
        // Extra M.2 drives only get a row once picked (see "Add Additional Storage")
        ...PRIMARY_EXTRA_SLOTS.filter(k => customBuildComponents[k]).map(k => ({ key: k, label: '💾 Storage', items: getComponentItems(k) })),
        { key: 'storage2', label: '🗃️ Secondary Storage (D:) — optional', items: getComponentItems('storage2') },
        // More secondary drives only get a row once picked (see "Add Additional Storage")
        ...EXTRA_STORAGE_SLOTS.slice(1).filter(k => customBuildComponents[k]).map(k => ({ key: k, label: '🗃️ Secondary Storage', items: getComponentItems(k) })),
        { key: 'gpu', label: '🎮 Graphics Card (GPU)', items: getComponentItems('gpu') },
        { key: 'psu', label: '⚡ Power Supply (PSU)', items: getComponentItems('psu') },
        { key: 'case', label: '🗄️ PC Case', items: getComponentItems('case') }
    ];

    // Build a short "filtered by…" hint per category
    function compatHint(key) {
        if (CHECK_ON_CLICK) return '';
        const cb = customBuildComponents;
        if (key === 'motherboard' && cb.cpu) {
            const s = compatSocket(cb.cpu);
            return s ? `Showing only <strong>${s}</strong> boards compatible with ${cb.cpu.name}` : '';
        }
        if (key === 'cpu' && cb.motherboard) {
            const s = compatSocket(cb.motherboard);
            return s ? `Showing only <strong>${s}</strong> CPUs for ${cb.motherboard.name}` : '';
        }
        if (key === 'ram' && cb.motherboard) {
            const r = compatRam(cb.motherboard);
            return r ? `Showing only <strong>${r}</strong> memory for your motherboard` : '';
        }
        if (key === 'case' && cb.motherboard) {
            return `Showing cases that fit a <strong>${moboForm(cb.motherboard)}</strong> motherboard`;
        }
        return '';
    }

    const hasAnyParts = componentTypes.some(type => type.items.length > 0);
    
    if (!hasAnyParts) {
        container.innerHTML = `
            <div class="no-items-message">
                <i class="fas fa-box-open"></i>
                <h3>No items available</h3>
                <p>This store doesn't have any ${currentBrandFilter !== 'all' ? getBrandDisplayName(currentBrandFilter) + ' ' : ''}components in their inventory yet.</p>
                <p style="margin-top: 16px; color: var(--text-muted);">Please select a different store or brand filter.</p>
                <button class="btn-primary" style="margin-top: 20px;" onclick="focusStoreSelect('customStoreGrid')">Change Store</button>
            </div>
        `;
        return;
    }

    // "+ Add Additional Storage" goes right under the last Secondary Storage
    // row, once a secondary drive is chosen and there's still a free slot.
    function ppAddStorageRow(key) {
        if (key === 'ram' || isRamExtraSlot(key)) return ppAddRamRow(key);
        if (key === 'storage' || isPrimaryExtraSlot(key)) return ppAddM2Row(key);
        if (!isExtraStorageSlot(key) || !customBuildComponents.storage2) return '';
        const lastShown = EXTRA_STORAGE_SLOTS.filter(k => customBuildComponents[k]).pop();
        if (key !== lastShown) return '';
        const used = EXTRA_STORAGE_SLOTS.filter(k => customBuildComponents[k]).length;
        const max = maxSecondaryDrives();
        const ports = boardSataPorts(customBuildComponents);
        const free = used < max ? EXTRA_STORAGE_SLOTS.find(k => !customBuildComponents[k]) : null;
        if (!free) {
            const why = ports
                ? `All SATA ports on this motherboard are in use (${ports} port${ports === 1 ? '' : 's'}${mainDriveUsesSata(customBuildComponents) ? ', including your main drive' : ''}).`
                : `Maximum of ${max} secondary drives. Choose a motherboard to see how many it supports.`;
            return `<div class="pp-addmore pp-addmore-max"><i class="fas fa-circle-info"></i> ${why}</div>`;
        }
        return `<div class="pp-addmore"><button type="button" class="pp-choose pp-choose-sm" onclick="openPartPicker('${free}')"><i class="fas fa-plus"></i> Add Additional Storage</button></div>`;
    }

    // "+ Add Additional Memory" under the last Memory row, limited by the
    // motherboard's RAM slots (counted in sticks: a 2x16GB kit uses 2).
    function ppAddRamRow(key) {
        const cb = customBuildComponents;
        if (!cb.ram) return '';
        const lastShown = ['ram', ...RAM_EXTRA_SLOTS.filter(k => cb[k])].pop();
        if (key !== lastShown) return '';
        const slots = boardRamSlots(cb);
        const used = ramSticksUsed(cb);
        const free = freeRamSlots(cb);
        const next = RAM_EXTRA_SLOTS.find(k => !cb[k]);
        // Something must still fit: a free slot and at least one kit small enough.
        const canAdd = free > 0 && next && getComponentItems(next).length > 0;
        if (!canAdd) {
            let why;
            if (!cb.motherboard) why = `Choose a motherboard to add more memory — it depends on how many RAM slots the board has.`;
            else if (!slots) why = `The store hasn't listed the RAM slots of ${escapeHtml(cb.motherboard.name)} yet, so up to ${DEFAULT_RAM_SLOTS} sticks are allowed (${used} used).`;
            else if (free > 0) why = `${free} RAM slot${free === 1 ? '' : 's'} free, but no compatible kit small enough is in stock.`;
            else why = `All ${slots} RAM slots on this motherboard are in use.`;
            return `<div class="pp-addmore pp-addmore-max"><i class="fas fa-circle-info"></i> ${why}</div>`;
        }
        return `<div class="pp-addmore"><button type="button" class="pp-choose pp-choose-sm" onclick="openPartPicker('${next}')"><i class="fas fa-plus"></i> Add Additional Memory</button></div>`;
    }

    // Under the main Memory row: RAM slots used / available.
    function ppRamHint() {
        const cb = customBuildComponents;
        const slots = boardRamSlots(cb);
        if (!cb.motherboard || !cb.ram || !slots) return '';
        const used = ramSticksUsed(cb);
        const gb = ['ram', ...RAM_EXTRA_SLOTS].reduce((n, k) => n + (cb[k] ? ramCapacityGB(cb[k]) : 0), 0);
        return `<div class="compat-hint pp-hint"><i class="fas fa-circle-check"></i> ${escapeHtml(cb.motherboard.name)} has <strong>${slots} RAM slot${slots === 1 ? '' : 's'}</strong> — ${used} used${gb ? `, <strong>${gb}GB</strong> total` : ''}.</div>`;
    }

    // Same idea for the main Storage group, limited by the board's M.2 slots.
    function ppAddM2Row(key) {
        const cb = customBuildComponents;
        if (!cb.storage) return '';
        const lastShown = ['storage', ...PRIMARY_EXTRA_SLOTS.filter(k => cb[k])].pop();
        if (key !== lastShown) return '';
        const used = PRIMARY_EXTRA_SLOTS.filter(k => cb[k]).length;
        const max = maxExtraM2Drives(cb);
        const slots = boardM2Slots(cb);
        const free = used < max ? PRIMARY_EXTRA_SLOTS.find(k => !cb[k]) : null;
        if (!free) {
            // Explain why there's no "Add Additional Storage" button.
            let why;
            if (!cb.motherboard) why = 'Choose a motherboard to add more NVMe/M.2 drives — it depends on how many M.2 slots the board has.';
            else if (!slots) why = `The store hasn't listed the M.2 slots of ${escapeHtml(cb.motherboard.name)} yet, so only 1 M.2 drive is allowed.`;
            else if (slots === 1) why = `${escapeHtml(cb.motherboard.name)} has only 1 M.2 slot${mainDriveUsesM2(cb) ? ', used by your main drive' : ''}.`;
            else why = `All M.2 slots on this motherboard are in use (${slots} slots${mainDriveUsesM2(cb) ? ', including your main drive' : ''}).`;
            return `<div class="pp-addmore pp-addmore-max"><i class="fas fa-circle-info"></i> ${why}</div>`;
        }
        return `<div class="pp-addmore"><button type="button" class="pp-choose pp-choose-sm" onclick="openPartPicker('${free}')"><i class="fas fa-plus"></i> Add Additional Storage</button></div>`;
    }

    // Under the main Storage row: how many M.2 drives the board allows.
    function ppM2Hint() {
        const cb = customBuildComponents;
        const slots = boardM2Slots(cb);
        if (!cb.motherboard || !cb.storage || !slots || slots <= 1) return '';
        const max = maxExtraM2Drives(cb);
        return `<div class="compat-hint pp-hint"><i class="fas fa-circle-check"></i> ${escapeHtml(cb.motherboard.name)} has <strong>${slots} M.2 slots</strong>${mainDriveUsesM2(cb) ? ' (1 used by your main drive)' : ''} — up to <strong>${max}</strong> more NVMe/M.2 drive${max === 1 ? '' : 's'}.</div>`;
    }

    // Under the first Secondary Storage row: how many drives the board allows.
    function ppSataHint() {
        const cb = customBuildComponents;
        const ports = boardSataPorts(cb);
        const max = maxSecondaryDrives(cb);
        if (!cb.motherboard) return '';
        if (!ports) return `<div class="compat-hint pp-hint"><i class="fas fa-circle-info"></i> This motherboard's SATA ports aren't listed by the store, so up to ${max} secondary drives are allowed.</div>`;
        return `<div class="compat-hint pp-hint"><i class="fas fa-circle-check"></i> ${escapeHtml(cb.motherboard.name)} has <strong>${ports} SATA port${ports === 1 ? '' : 's'}</strong>${mainDriveUsesSata(cb) ? ' (1 used by your main drive)' : ''} — up to <strong>${max}</strong> secondary drive${max === 1 ? '' : 's'}.</div>`;
    }

    // ---- PCPartPicker-style parts list: one row per component ----
    const showStoreTags = selectedStore === '__all__';
    const total = Object.values(customBuildComponents).reduce((sum, c) => sum + (c?.price || 0), 0);
    const chooseLabel = { cpu: 'Choose A CPU', motherboard: 'Choose A Motherboard', ram: 'Choose Memory',
        ram2: 'Add Additional Memory', ram3: 'Add Additional Memory', ram4: 'Add Additional Memory', ram5: 'Add Additional Memory', ram6: 'Add Additional Memory', ram7: 'Add Additional Memory', ram8: 'Add Additional Memory', ram9: 'Add Additional Memory', ram10: 'Add Additional Memory', ram11: 'Add Additional Memory', ram12: 'Add Additional Memory', ram13: 'Add Additional Memory', ram14: 'Add Additional Memory', ram15: 'Add Additional Memory', ram16: 'Add Additional Memory', ram17: 'Add Additional Memory', ram18: 'Add Additional Memory', ram19: 'Add Additional Memory', ram20: 'Add Additional Memory', ram21: 'Add Additional Memory', ram22: 'Add Additional Memory', ram23: 'Add Additional Memory', ram24: 'Add Additional Memory',
        storage: 'Choose Storage', storage2: 'Choose Secondary Storage', storage3: 'Add Additional Storage', storage4: 'Add Additional Storage', storage5: 'Add Additional Storage', storage6: 'Add Additional Storage', storage7: 'Add Additional Storage', storage8: 'Add Additional Storage', storage9: 'Add Additional Storage', nvme2: 'Add Additional Storage', nvme3: 'Add Additional Storage', nvme4: 'Add Additional Storage', nvme5: 'Add Additional Storage', nvme6: 'Add Additional Storage', nvme7: 'Add Additional Storage', gpu: 'Choose A Video Card',
        psu: 'Choose A Power Supply', case: 'Choose A Case' };
    const rowLabel = { cpu: 'CPU', motherboard: 'Motherboard', ram: 'Memory', storage: 'Storage',
        storage2: 'Secondary Storage', ram2: 'Memory', ram3: 'Memory', ram4: 'Memory', ram5: 'Memory', ram6: 'Memory', ram7: 'Memory', ram8: 'Memory', ram9: 'Memory', ram10: 'Memory', ram11: 'Memory', ram12: 'Memory', ram13: 'Memory', ram14: 'Memory', ram15: 'Memory', ram16: 'Memory', ram17: 'Memory', ram18: 'Memory', ram19: 'Memory', ram20: 'Memory', ram21: 'Memory', ram22: 'Memory', ram23: 'Memory', ram24: 'Memory', storage3: 'Secondary Storage', storage4: 'Secondary Storage', storage5: 'Secondary Storage', storage6: 'Secondary Storage', storage7: 'Secondary Storage', storage8: 'Secondary Storage', storage9: 'Secondary Storage', nvme2: 'Storage', nvme3: 'Storage', nvme4: 'Storage', nvme5: 'Storage', nvme6: 'Storage', nvme7: 'Storage', gpu: 'Video Card', psu: 'Power Supply', case: 'Case' };

    container.innerHTML = `
        <div class="pp-topbar">
            <div class="pp-compat" id="ppCompatBar"></div>
            <div class="pp-total"><i class="fas fa-peso-sign"></i> Total: ₱${total.toLocaleString()}</div>
        </div>
        <div class="pp-list">
            <div class="pp-head">
                <span>Component</span><span>Selection</span><span>Availability</span><span class="pp-r">Price</span><span>${showStoreTags ? 'Where' : ''}</span><span></span>
            </div>
            ${componentTypes.map(type => {
                const sel = customBuildComponents[type.key];
                const hint = compatHint(type.key);
                const st = sel ? stockStatus(sel) : null;
                const optional = isExtraStorageSlot(type.key);
                return `
                <div class="component-selector pp-row" data-part="${type.key}">
                    <div class="selector-header pp-line">
                        <span class="pp-comp">${rowLabel[type.key]}${optional ? '<small>optional</small>' : ''}</span>
                        ${sel ? `
                            <span class="pp-sel">
                                <button type="button" class="pp-detail-btn pp-sel-photo" onclick="showSelectedPartDetails('${type.key}')" title="View details">${sel.image ? `<img src="${escapeHtml(sel.image)}" alt="" loading="lazy">` : `<span class="pp-noimg"><i class="fas fa-microchip"></i></span>`}</button>
                                <span class="pp-sel-text">
                                    <button type="button" class="pp-sel-name" onclick="openPartPicker('${type.key}')" title="Change">${escapeHtml(ppDisplayName(sel))}</button>
                                    ${sel.specs ? `<small>${escapeHtml(sel.specs)}</small>` : ''}
                                </span>
                            </span>
                            <span class="pp-avail ${st && st.cssClass ? st.cssClass : ''}">${st && st.label ? `${st.dot} ${escapeHtml(st.label)}` : '—'}</span>
                            <span class="pp-price pp-r">₱${(sel.price || 0).toLocaleString()}</span>
                            <span class="pp-where">${showStoreTags && sel.storeName ? escapeHtml(sel.storeName) : ''}</span>
                            <span class="pp-actions">
                                <button type="button" class="pp-icon" onclick="openPartPicker('${type.key}')" title="Change"><i class="fas fa-pen"></i></button>
                                <button type="button" class="pp-icon pp-icon-x" onclick="clearComponent('${type.key}')" title="Remove"><i class="fas fa-xmark"></i></button>
                            </span>
                        ` : `
                            <span class="pp-sel">
                                ${type.key === 'storage2' && maxSecondaryDrives() === 0
                                    ? `<span class="pp-none"><i class="fas fa-circle-info"></i> No free SATA port on this motherboard.</span>`
                                    : type.items.length
                                    ? `<button type="button" class="pp-choose" onclick="openPartPicker('${type.key}')"><i class="fas fa-plus"></i> ${chooseLabel[type.key]}</button>`
                                    : `<span class="pp-none"><i class="fas fa-triangle-exclamation"></i> ${hint ? 'No compatible option here — try another store or change an earlier part.' : 'No items available in this store.'}</span>`}
                            </span>
                            <span></span><span></span><span></span><span></span>
                        `}
                    </div>
                    ${hint && !sel ? `<div class="compat-hint pp-hint"><i class="fas fa-circle-check"></i> ${hint}</div>` : ''}
                    ${type.key === 'storage2' ? ppSataHint() : ''}
                    ${type.key === 'storage' ? ppM2Hint() : ''}
                    ${type.key === 'ram' ? ppRamHint() : ''}
                </div>${ppAddStorageRow(type.key)}`;
            }).join('')}
        </div>
    `;

    // No more "Check Compatibility" button: the picker already blocks parts
    // that don't fit, and the Build Advice message below updates by itself.
    compatChecked = true;
    checkFullCompatibility();
}

// =====================
// Part picker (PCPartPicker-style product list in a pop-up)
// =====================
let ppState = { key: null, sort: 'price', dir: 1, q: '' };

// "Ryzen 5 5600G" -> "AMD Ryzen 5 5600G", but "AMD Ryzen 5 5600G" stays as-is.
function ppDisplayName(item) {
    const brand = String(item.brandName || item.brand || '').trim();
    const name = String(item.name || '').trim();
    if (!brand || name.toLowerCase().includes(brand.toLowerCase())) return name;
    return brand + ' ' + name;
}

// Short spec tags shown in the parts picker (only the ones the item has).
function ppSpecTags(key, i) {
    const t = [];
    const push = v => { if (v) t.push(v); };
    if (key === 'cpu') { push(compatSocket(i) || i.socket); const c = cpuCoreCount(i); push(c ? c + ' cores' : ''); }
    else if (key === 'motherboard') {
        push(compatSocket(i) || i.socket); push(compatRam(i) || i.ram_type); push(i.form_factor);
        push(i.ram_slots ? i.ram_slots + ' RAM slots' : ''); push(i.m2_slots ? i.m2_slots + ' M.2' : ''); push(i.sata_ports ? i.sata_ports + ' SATA' : '');
    }
    else if (key === 'ram' || isRamExtraSlot(key)) {
        push(compatRam(i) || i.ram_type); const gb = ramCapacityGB(i); push(gb ? gb + 'GB' : '');
        const n = ramSticks(i); push(n + (n === 1 ? ' stick' : ' sticks'));
    }
    else if (key === 'storage' || isExtraStorageSlot(key) || isPrimaryExtraSlot(key)) {
        push(i.storage_type || (isM2Storage(i) ? 'NVMe' : isHardDisk(i) ? 'HDD' : ''));
        const gb = storageCapacityGB(i); push(gb ? (gb >= 1000 ? (gb / 1000) + 'TB' : gb + 'GB') : '');
    }
    else if (key === 'psu') { const w = extractWattage(i); push(w ? w + 'W' : ''); }
    else if (key === 'case') push(i.form_factor);
    return t;
}

function openPartPicker(key) {
    ppState = { key, sort: 'price', dir: 1, q: '' };
    let modal = document.getElementById('ppModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'ppModal';
        modal.className = 'pp-modal';
        modal.addEventListener('click', e => { if (e.target === modal) closePartPicker(); });
        document.body.appendChild(modal);
        document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) closePartPicker(); });
    }
    const titles = { cpu: 'Choose A CPU', motherboard: 'Choose A Motherboard', ram: 'Choose Memory', storage: 'Choose Storage',
        ram2: 'Add Additional Memory', ram3: 'Add Additional Memory', ram4: 'Add Additional Memory', ram5: 'Add Additional Memory', ram6: 'Add Additional Memory', ram7: 'Add Additional Memory', ram8: 'Add Additional Memory', ram9: 'Add Additional Memory', ram10: 'Add Additional Memory', ram11: 'Add Additional Memory', ram12: 'Add Additional Memory', ram13: 'Add Additional Memory', ram14: 'Add Additional Memory', ram15: 'Add Additional Memory', ram16: 'Add Additional Memory', ram17: 'Add Additional Memory', ram18: 'Add Additional Memory', ram19: 'Add Additional Memory', ram20: 'Add Additional Memory', ram21: 'Add Additional Memory', ram22: 'Add Additional Memory', ram23: 'Add Additional Memory', ram24: 'Add Additional Memory',
        storage2: 'Choose Secondary Storage', storage3: 'Add Additional Storage', storage4: 'Add Additional Storage', storage5: 'Add Additional Storage', storage6: 'Add Additional Storage', storage7: 'Add Additional Storage', storage8: 'Add Additional Storage', storage9: 'Add Additional Storage', nvme2: 'Add Additional Storage', nvme3: 'Add Additional Storage', nvme4: 'Add Additional Storage', nvme5: 'Add Additional Storage', nvme6: 'Add Additional Storage', nvme7: 'Add Additional Storage', gpu: 'Choose A Video Card', psu: 'Choose A Power Supply', case: 'Choose A Case' };
    modal.innerHTML = `
        <div class="pp-dialog" role="dialog" aria-modal="true">
            <div class="pp-dialog-head">
                <h3>${titles[key] || 'Choose a part'}</h3>
                <button type="button" class="pp-icon" onclick="closePartPicker()" title="Close"><i class="fas fa-xmark"></i></button>
            </div>
            <div class="pp-dialog-tools">
                <div class="pp-search"><i class="fas fa-search"></i><input type="search" id="ppSearch" placeholder="Search name, brand or specs…" autocomplete="off" oninput="ppState.q=this.value.trim().toLowerCase(); renderPartPickerRows();"></div>
                <span class="pp-count" id="ppCount"></span>
            </div>
            <div class="pp-table-wrap"><table class="pp-table" id="ppTable"></table></div>
        </div>`;
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
    renderPartPickerRows();
    setTimeout(() => { const s = document.getElementById('ppSearch'); if (s) s.focus(); }, 50);
}

function closePartPicker() {
    const modal = document.getElementById('ppModal');
    if (modal) modal.classList.remove('open');
    document.body.style.overflow = '';
}

function ppSortBy(col) {
    if (ppState.sort === col) ppState.dir = -ppState.dir; else { ppState.sort = col; ppState.dir = 1; }
    renderPartPickerRows();
}

// Picker: why an item can't go with the parts already chosen (or '' if it
// fits). Uses the same rules as the Build Advice check, but only
// counts NEW critical problems this item would cause, so an earlier mistake
// elsewhere in the build doesn't grey out everything.
function ppBaselineIssues(key) {
    return new Set(computeCompatIssues({ ...customBuildComponents, [key]: null })
        .filter(x => x.type === 'critical').map(x => x.component + '|' + (x.parts || []).join(',')));
}

function ppIncompatReason(key, item, before) {
    const cb = customBuildComponents;
    const crit = list => list.filter(x => x.type === 'critical');
    before = before || ppBaselineIssues(key);
    const added = crit(computeCompatIssues({ ...cb, [key]: item }))
        .filter(x => !before.has(x.component + '|' + (x.parts || []).join(',')));
    if (!added.length) return '';
    const x = added[0];
    const base = isRamExtraSlot(key) ? 'ram' : (isExtraStorageSlot(key) || isPrimaryExtraSlot(key)) ? 'storage' : key;
    const ff = p => p && (p.formFactor || p.form_factor);
    switch (x.component) {
        case 'CPU/Motherboard':
            return base === 'cpu'
                ? `Your motherboard is ${compatSocket(cb.motherboard)} — this CPU is ${compatSocket(item)}`
                : `Your CPU is ${compatSocket(cb.cpu)} — this board is ${compatSocket(item)}`;
        case 'RAM/Motherboard':
            return base === 'ram'
                ? `Your motherboard uses ${compatRam(cb.motherboard)} — this is ${compatRam(item)}`
                : `Your memory is ${compatRam(cb.ram)} — this board uses ${compatRam(item)}`;
        case 'Memory':
            return `Different memory type from your other kit`;
        case 'Memory/Motherboard':
            return base === 'motherboard'
                ? `Only ${boardRamSlots({ ...cb, motherboard: item })} RAM slots — you picked ${ramSticksUsed(cb)} sticks`
                : `Not enough free RAM slots on your motherboard`;
        case 'Storage/Motherboard':
            return /M\.2/.test(x.issue)
                ? (base === 'motherboard' ? `Not enough M.2 slots for your drives` : `No free M.2 slot on your motherboard`)
                : (base === 'motherboard' ? `Not enough SATA ports for your drives` : `No free SATA port on your motherboard`);
        case 'Power Supply': {
            const m = x.issue.match(/(\d{3,4})W\s*—/);
            if (base === 'psu') return m ? `Too weak — your build needs at least ${m[1]}W` : `Too weak for your build`;
            return `Too power-hungry for your ${extractWattage(cb.psu)}W power supply`;
        }
        case 'Case/Motherboard':
            return base === 'case'
                ? `Too small for your ${ff(cb.motherboard)} motherboard`
                : `Too big for your ${ff(cb.case)} case`;
        default:
            return x.issue;
    }
}

function renderPartPickerRows() {
    const key = ppState.key;
    const table = document.getElementById('ppTable');
    if (!table || !key) return;
    const showStore = selectedStore === '__all__';
    const current = customBuildComponents[key];
    let items = getComponentItems(key).slice();
    if (ppState.q) {
        items = items.filter(i => [i.name, i.brand, i.brandName, i.specs, i.socket, i.ram_type, i.form_factor, i.storage_type, i.storeName]
            .some(v => String(v || '').toLowerCase().includes(ppState.q)));
    }
    // Every item is listed; the ones that don't fit what's already chosen
    // are greyed out (and can't be added), listed after the ones that fit.
    const baseline = ppBaselineIssues(key);
    const reasons = new Map(items.map(i => [i, ppIncompatReason(key, i, baseline)]));
    const d = ppState.dir;
    items.sort((a, b) => {
        const ra = reasons.get(a) ? 1 : 0, rb = reasons.get(b) ? 1 : 0;
        if (ra !== rb) return ra - rb;
        if (ppState.sort === 'name') return d * String(a.name).localeCompare(String(b.name));
        if (ppState.sort === 'stock') return d * ((b.stock || 0) - (a.stock || 0));
        return d * ((a.price || 0) - (b.price || 0));
    });
    const arrow = col => ppState.sort === col ? (ppState.dir === 1 ? ' <i class="fas fa-caret-up"></i>' : ' <i class="fas fa-caret-down"></i>') : ' <i class="fas fa-sort pp-dim"></i>';
    const countEl = document.getElementById('ppCount');
    const blocked = items.filter(i => reasons.get(i)).length;
    const okCount = items.length - blocked;
    if (countEl) countEl.innerHTML = blocked
        ? `<strong class="pp-count-ok">${okCount} compatible</strong> · <span class="pp-count-no">${blocked} not compatible</span>`
        : items.length + (items.length === 1 ? ' product' : ' products');

    table.innerHTML = `
        <thead><tr>
            <th></th>
            <th class="pp-sortable" onclick="ppSortBy('name')">Name${arrow('name')}</th>
            <th>Specs</th>
            <th class="pp-sortable" onclick="ppSortBy('stock')">Availability${arrow('stock')}</th>
            ${showStore ? '<th>Store</th>' : ''}
            <th class="pp-sortable pp-r" onclick="ppSortBy('price')">Price${arrow('price')}</th>
            <th class="pp-sticky"></th>
        </tr></thead>
        <tbody>
        ${items.length ? items.map(i => {
            const st = stockStatus(i);
            const isCur = !!(current && current.id === i.id);
            const why = reasons.get(i);
            const firstBlocked = why && (items.indexOf(i) === 0 || !reasons.get(items[items.indexOf(i) - 1]));
            return `${firstBlocked ? `<tr class="pp-divider"><td colspan="${6 + (showStore ? 1 : 0)}"><i class="fas fa-ban"></i> Not compatible with the parts you already chose</td></tr>` : ''}
            <tr class="${isCur ? 'pp-current' : ''}${why ? ' pp-blocked' : ''}"${why ? ` title="${escapeHtml(why)}"` : ''}>
                <td class="pp-thumb"><button type="button" class="pp-detail-btn" data-id="${escapeHtml(i.id)}" onclick="ppShowDetails(this)" title="View details">${i.image ? `<img src="${escapeHtml(i.image)}" alt="" loading="lazy">` : `<span class="pp-noimg"><i class="fas fa-microchip"></i></span>`}</button></td>
                <td class="pp-name"><button type="button" class="pp-name-btn" data-id="${escapeHtml(i.id)}" onclick="ppShowDetails(this)" title="View details"><strong>${escapeHtml(ppDisplayName({ ...i, brandName: i.brandName || i.brand }))}</strong></button>${why ? `<span class="pp-why"><i class="fas fa-ban"></i> ${escapeHtml(why)}</span>` : ''}${i.specs ? `<small>${escapeHtml(i.specs)}</small>` : ''}</td>
                <td class="pp-tags">${ppSpecTags(key, i).map(v => `<span>${escapeHtml(String(v))}</span>`).join('') || '—'}</td>
                <td class="pp-avail ${st.cssClass}">${st.label ? `${st.dot} ${escapeHtml(st.label)}` : '—'}</td>
                ${showStore ? `<td>${escapeHtml(i.storeName || '')}</td>` : ''}
                <td class="pp-price pp-r">₱${(i.price || 0).toLocaleString()}</td>
                <td class="pp-r pp-sticky">${isCur
                    ? '<span class="pp-picked"><i class="fas fa-check"></i> Selected</span>'
                    : why
                    ? `<button type="button" class="pp-add" disabled aria-disabled="true" title="${escapeHtml(why)}"><i class="fas fa-ban"></i> Add</button>`
                    : `<button type="button" class="pp-add" data-id="${escapeHtml(i.id)}" data-store="${escapeHtml(i.storeId || '')}" onclick="ppAdd(this)"><i class="fas fa-plus"></i> Add</button>`}</td>
            </tr>`;
        }).join('') : `<tr><td colspan="${6 + (showStore ? 1 : 0)}" class="pp-empty">${ppState.q ? 'No products match your search.' : 'No compatible products available.'}</td></tr>`}
        </tbody>`;
}

// Parts list: click the photo of a chosen part to see its full details,
// with a button to swap it for another part.
function showSelectedPartDetails(key) {
    const item = customBuildComponents[key];
    if (!item) return;
    openPartDetailsFor(item, {
        addLabel: 'Change part',
        addIcon: 'fa-pen',
        canAdd: true,
        onAdd: `closePartDetails(); openPartPicker('${key}')`
    });
}

// Picker: click a part's photo or name to see it big, with an Add button.
function ppShowDetails(btn) {
    const key = ppState.key;
    const item = getComponentItems(key).find(i => i.id === btn.dataset.id);
    if (!item) return;
    const isCur = customBuildComponents[key] && customBuildComponents[key].id === item.id;
    const why = isCur ? '' : ppIncompatReason(key, item);
    openPartDetailsFor(item, {
        addLabel: 'Add to build',
        canAdd: !isCur && !why,
        blockedReason: why,
        onAdd: `closePartDetails(); ppAdd({ dataset: { id: '${String(item.id).replace(/[^A-Za-z0-9_\-]/g, '')}' } })`
    });
}

function ppAdd(btn) {
    const key = ppState.key;
    const item = getComponentItems(key).find(i => i.id === btn.dataset.id);
    if (item && ppIncompatReason(key, item)) return;   // greyed-out part
    closePartPicker();
    updateComponent(key, btn.dataset.id);
}

// Check-on-click mode: nothing is reported until the button is pressed.
function showCompatPrompt() {
    compatibilityIssues = [];
    const reportContainer = document.getElementById('compatibilityReport');
    if (!reportContainer) return;
    const picked = Object.values(customBuildComponents).some(c => c);
    reportContainer.innerHTML = picked ? `
        <div class="compatibility-report">
            <h3><i class="fas fa-clipboard-list"></i> Compatibility Report</h3>
            <div class="report-item info">
                <div class="report-issue"><i class="fas fa-circle-info"></i> Not checked yet.</div>
                <div class="report-solution">Press <strong>Check Compatibility</strong> to see if your selected parts work together.</div>
            </div>
        </div>` : '';
    updatePartsCompatBar();
}

// Auto-fill compatible parts based on the chosen CPU platform (Intel/AMD)
function autoRecommend(changed) {
    const cb = customBuildComponents;
    // storesData['__all__'] isn't a real store, so only bail out here for the
    // single-store case — All Stores mode is handled fine by getComponentItems.
    if (selectedStore !== '__all__' && (!selectedStore || !storesData[selectedStore])) return [];
    const filled = [];
    function pickBest(category) {
        const items = getComponentItems(category); // already compatibility-filtered
        if (!items.length) return null;
        return items.slice().sort((a, b) => {
            const as = (a.stock > 0 ? 0 : 1), bs = (b.stock > 0 ? 0 : 1);
            if (as !== bs) return as - bs;            // in-stock first
            return (a.price || 0) - (b.price || 0);   // then cheapest
        })[0];
    }
    function setSel(category, item, label) {
        // item.storeName/storeId are already attached correctly by
        // getComponentItems() for both single-store and All Stores modes.
        cb[category] = {
            ...item, isStoreItem: true,
            storeName: item.storeName, storeId: item.storeId, brandName: item.brand
        };
        filled.push(label);
    }
    if (changed === 'cpu' && cb.cpu) {
        if (!cb.motherboard) { const mb = pickBest('motherboard'); if (mb) setSel('motherboard', mb, 'motherboard'); }
        if (cb.motherboard && !cb.ram) { const r = pickBest('ram'); if (r) setSel('ram', r, 'RAM'); }
    } else if (changed === 'motherboard' && cb.motherboard && !cb.ram) {
        const r = pickBest('ram'); if (r) setSel('ram', r, 'RAM');
    }
    return filled;
}

function updateComponent(componentType, componentId) {
    if (!componentId) {
        customBuildComponents[componentType] = null;
    } else {
        let item = null;

        // Find the picked item. In "All Stores" mode, selectedStore is the
        // literal string '__all__' — storesData['__all__'] doesn't exist, so
        // the single-store lookup below must search across every store's
        // inventory instead (this was previously silently dropping every
        // pick made while in All Stores mode).
        if (selectedStore === '__all__') {
            for (const storeId in storesData) {
                const foundItem = (storesData[storeId].inventory || []).find(i => i.id === componentId);
                if (foundItem) {
                    item = {
                        ...foundItem,
                        isStoreItem: true,
                        storeName: storesData[storeId].name,
                        storeId: storeId,
                        brandName: foundItem.brand
                    };
                    break;
                }
            }
        } else if (selectedStore && storesData[selectedStore]) {
            const foundItem = storesData[selectedStore].inventory.find(i => i.id === componentId);
            if (foundItem) {
                item = {
                    ...foundItem,
                    isStoreItem: true,
                    storeName: storesData[selectedStore].name,
                    storeId: selectedStore,
                    brandName: foundItem.brand
                };
            }
        }

        customBuildComponents[componentType] = item;
    }
    compatChecked = false;   // the selection changed, so any earlier result is out of date
    if (CHECK_ON_CLICK) {
        // Free picking: no auto-removal, no auto-pick, no instant warning.
        renderComponentSelectors();
        updateSummaryPanel();
        return;
    }
    pruneIncompatible();
    // After picking a CPU/motherboard, auto-recommend the compatible platform parts
    if (componentId) {
        const filled = autoRecommend(componentType);
        if (filled.length) {
            const sock = compatSocket(customBuildComponents.cpu) || '';
            const plat = sock.indexOf('AM') === 0 ? 'AMD' : (sock.indexOf('LGA') === 0 ? 'Intel' : '');
            showToast(`✓ Auto-picked compatible ${filled.join(' & ')}${plat ? ` for your ${plat} build` : ''}`);
        }
    }
    renderComponentSelectors(); // internally runs checkFullCompatibility(), refreshing compatibilityIssues
    updateSummaryPanel();

    // Give instant feedback the moment an incompatible part is picked, instead
    // of only surfacing it in the compatibility report further down the page.
    if (componentId) {
        const critical = compatibilityIssues.filter(i => i.type === 'critical');
        if (critical.length) {
            showWarningToast(critical[0].issue + (critical.length > 1 ? ` (+${critical.length - 1} more issue${critical.length > 2 ? 's' : ''})` : ''));
        }
    }
}

// Keeps the secondary drives packed: removing one moves the next one up,
// so there's never an empty "Secondary Storage" row between filled ones.
function compactStorage() {
    const cb = customBuildComponents;
    const drives = EXTRA_STORAGE_SLOTS.map(k => cb[k]).filter(Boolean);
    EXTRA_STORAGE_SLOTS.forEach((k, i) => { cb[k] = drives[i] || null; });
}

// Same for the extra memory kits under the main Memory row.
function compactRam() {
    const cb = customBuildComponents;
    const kits = RAM_EXTRA_SLOTS.map(k => cb[k]).filter(Boolean);
    RAM_EXTRA_SLOTS.forEach((k, i) => { cb[k] = kits[i] || null; });
}

// Same for the extra M.2 drives under the main Storage row.
function compactM2Storage() {
    const cb = customBuildComponents;
    const drives = PRIMARY_EXTRA_SLOTS.map(k => cb[k]).filter(Boolean);
    PRIMARY_EXTRA_SLOTS.forEach((k, i) => { cb[k] = drives[i] || null; });
}

function clearComponent(componentType) {
    customBuildComponents[componentType] = null;
    if (isExtraStorageSlot(componentType)) compactStorage();
    if (isPrimaryExtraSlot(componentType)) compactM2Storage();
    if (isRamExtraSlot(componentType)) compactRam();
    // Removing the main kit: the next extra kit becomes the main one
    if (componentType === 'ram' && RAM_EXTRA_SLOTS.some(k => customBuildComponents[k])) {
        const first = RAM_EXTRA_SLOTS.find(k => customBuildComponents[k]);
        customBuildComponents.ram = customBuildComponents[first];
        customBuildComponents[first] = null;
        compactRam();
    }
    compatChecked = false;
    renderComponentSelectors();
    updateSummaryPanel();
}

// Some store-sourced PSU items only have a free-text "specs" string like
// "650W Bronze" instead of a structured wattage number — this pulls the
// number out either way so the compatibility check works for both.
function extractWattage(item) {
    if (!item) return null;
    if (typeof item.wattage === 'number' && item.wattage > 0) return item.wattage;
    const match = (item.specs || item.name || '').match(/(\d{3,4})\s*W/i);
    return match ? parseInt(match[1], 10) : null;
}

// =====================
// Bottleneck check — a rough performance tier (1 = entry … 5 = flagship)
// for the CPU and the GPU. Read from the model name first; when the name
// isn't recognised, estimated from the price. Only ever a WARNING: an
// unbalanced PC still works, it just wastes part of the stronger part.
// =====================
function performanceTier(item, kind) {
    if (!item) return null;
    const n = ((item.name || '') + ' ' + (item.brand || '')).toUpperCase();
    let m, tier = null;

    if (kind === 'cpu') {
        if (/THREADRIPPER|\bI9\b|I9[\s-]|RYZEN\s*9|ULTRA\s*9/.test(n)) tier = 5;
        else if (/\bI7\b|I7[\s-]|RYZEN\s*7|ULTRA\s*7/.test(n)) tier = 4;
        else if (/\bI5\b|I5[\s-]|RYZEN\s*5|ULTRA\s*5/.test(n)) tier = 3;
        else if (/\bI3\b|I3[\s-]|RYZEN\s*3/.test(n)) tier = 2;
        else if (/PENTIUM|CELERON|ATHLON/.test(n)) tier = 1;
        if (tier) {
            // Older generations are a step slower than their name suggests.
            m = n.match(/I[3579][\s-]*(\d{4,5})/);
            if (m) { const gen = m[1].length === 5 ? +m[1].slice(0, 2) : +m[1][0]; if (gen <= 9) tier--; }
            m = n.match(/RYZEN\s*\d\s*(?:PRO\s*)?(\d)\d{3}/);
            if (m && +m[1] <= 3) tier--;
            return { tier: Math.max(1, tier), how: 'model' };
        }
        const p = item.price || 0;
        if (!p) return null;
        return { tier: p < 6000 ? 1 : p < 10000 ? 2 : p < 16000 ? 3 : p < 25000 ? 4 : 5, how: 'price' };
    }

    // GPU
    if ((m = n.match(/RTX\s*(\d{2})(\d{2})/))) {           // RTX 4060 -> series 40, class 60
        const cls = +m[2], series = +m[1];
        tier = cls >= 80 ? 5 : cls >= 70 ? 4 : cls >= 60 ? 3 : 2;
        if (series <= 20) tier--;
    } else if (/GTX\s*16\d0/.test(n)) tier = 2;
    else if (/GTX|GT\s*\d{3,4}/.test(n)) tier = 1;
    else if ((m = n.match(/RX\s*(\d)(\d)\d{2}/))) {        // RX 7600 -> class 6
        const cls = +m[2];
        tier = cls >= 9 ? 5 : cls >= 7 ? 4 : cls >= 6 ? 3 : cls >= 5 ? 2 : 1;
        if (+m[1] <= 5) tier--;
    } else if (/RX\s*\d{3}\b/.test(n)) tier = 1;             // RX 580 and older
    else if ((m = n.match(/ARC\s*[AB](\d)\d{2}/))) tier = +m[1] >= 7 ? 3 : +m[1] >= 5 ? 3 : 1;
    if (tier) return { tier: Math.max(1, tier), how: 'model' };
    const p = item.price || 0;
    if (!p) return null;
    return { tier: p < 8000 ? 1 : p < 15000 ? 2 : p < 25000 ? 3 : p < 45000 ? 4 : 5, how: 'price' };
}

function bottleneckIssue(cpu, gpu) {
    const c = performanceTier(cpu, 'cpu'), g = performanceTier(gpu, 'gpu');
    if (!c || !g) return null;
    const gap = g.tier - c.tier;
    const basis = (c.how === 'price' || g.how === 'price') ? ' (estimated from price)' : '';
    if (gap >= 2) {
        return {
            type: 'warning', kind: 'bottleneck', component: 'CPU/GPU Bottleneck', parts: ['cpu', 'gpu'],
            issue: `CPU bottleneck: ${cpu.name} is much weaker than ${gpu.name}${basis}. The CPU will hold the GPU back, so you won't get the GPU's full performance.`,
            solution: `Choose a stronger CPU, or a cheaper GPU and spend the difference elsewhere.`
        };
    }
    if (gap <= -2) {
        return {
            type: 'warning', kind: 'bottleneck', component: 'CPU/GPU Bottleneck', parts: ['cpu', 'gpu'],
            issue: `GPU bottleneck: ${gpu.name} is much weaker than ${cpu.name}${basis}. In games the GPU will be the limit, so most of the CPU's power goes unused.`,
            solution: `For gaming, choose a stronger GPU, or a cheaper CPU and put the savings into the GPU.`
        };
    }
    return null;
}

// Every compatibility rule, applied to any set of parts. Used by the Check
// Compatibility button AND by the build generator, so a generated build is
// held to exactly the same rules the customer will check it against.
function computeCompatIssues(parts) {
    const issues = [];

    if (parts.cpu && parts.motherboard) {
        const cpuSock = compatSocket(parts.cpu);
        const mbSock = compatSocket(parts.motherboard);
        if (cpuSock && mbSock && cpuSock !== mbSock) {
            issues.push({
                type: 'critical',
                component: 'CPU/Motherboard', parts: ['cpu', 'motherboard'],
                issue: `Socket mismatch: ${parts.cpu.name} uses ${cpuSock}, but ${parts.motherboard.name} uses ${mbSock}.`,
                solution: `Choose a motherboard with ${cpuSock} socket.`
            });
        }
    }

    // Memory: more sticks than the motherboard has RAM slots, or mixed types
    const ramSlots = boardRamSlots(parts);
    if (ramSlots && parts.ram) {
        const sticks = ramSticksUsed(parts);
        if (sticks > ramSlots) {
            issues.push({
                type: 'critical',
                component: 'Memory/Motherboard', parts: ['motherboard', 'ram'],
                issue: `Too many memory sticks: you picked ${sticks}, but ${parts.motherboard.name} only has ${ramSlots} RAM slot${ramSlots === 1 ? '' : 's'}.`,
                solution: `Remove ${sticks - ramSlots} stick${sticks - ramSlots === 1 ? '' : 's'} of memory, or choose a motherboard with more RAM slots.`
            });
        }
    }
    if (parts.ram) {
        const types = [...new Set(['ram', ...RAM_EXTRA_SLOTS].filter(k => parts[k]).map(k => compatRam(parts[k])).filter(Boolean))];
        if (types.length > 1) {
            issues.push({
                type: 'critical',
                component: 'Memory', parts: ['ram'],
                issue: `Mixed memory types: ${types.join(' and ')} can't be used together.`,
                solution: `Use only ${compatRam(parts.ram) || types[0]} memory kits.`
            });
        }
    }

    // More M.2 drives than the motherboard has M.2 slots
    const m2Slots = boardM2Slots(parts);
    if (m2Slots) {
        const m2Drives = PRIMARY_EXTRA_SLOTS.filter(k => parts[k]).length + (mainDriveUsesM2(parts) ? 1 : 0);
        if (m2Drives > m2Slots) {
            issues.push({
                type: 'critical',
                component: 'Storage/Motherboard', parts: ['motherboard', 'storage'],
                issue: `Too many M.2 drives: you picked ${m2Drives}, but ${parts.motherboard.name} only has ${m2Slots} M.2 slot${m2Slots === 1 ? '' : 's'}.`,
                solution: `Remove ${m2Drives - m2Slots} M.2 drive${m2Drives - m2Slots === 1 ? '' : 's'}, or choose a motherboard with more M.2 slots.`
            });
        }
    }

    // More SATA drives than the motherboard has SATA ports
    const sataPorts = boardSataPorts(parts);
    if (sataPorts) {
        const sataDrives = EXTRA_STORAGE_SLOTS.filter(k => parts[k]).length + (mainDriveUsesSata(parts) ? 1 : 0);
        if (sataDrives > sataPorts) {
            issues.push({
                type: 'critical',
                component: 'Storage/Motherboard', parts: ['motherboard', 'storage2'],
                issue: `Too many SATA drives: you picked ${sataDrives}, but ${parts.motherboard.name} only has ${sataPorts} SATA port${sataPorts === 1 ? '' : 's'}.`,
                solution: `Remove ${sataDrives - sataPorts} secondary drive${sataDrives - sataPorts === 1 ? '' : 's'}, or choose a motherboard with more SATA ports.`
            });
        }
    }

    if (parts.ram && parts.motherboard) {
        const ramT = compatRam(parts.ram);
        const mbT = compatRam(parts.motherboard);
        if (ramT && mbT && ramT !== mbT) {
            issues.push({
                type: 'critical',
                component: 'RAM/Motherboard', parts: ['ram', 'motherboard'],
                issue: `RAM type mismatch: ${parts.ram.name} is ${ramT}, but motherboard supports ${mbT}.`,
                solution: `Choose ${mbT} RAM.`
            });
        }
    }

    if (parts.psu && (parts.cpu || parts.gpu)) {
        const cpuNeed = parts.cpu ? (parts.cpu.tdp || 65) : 0;
        const gpuNeed = parts.gpu ? (parts.gpu.power || 200) : 0;
        const overhead = 50; // motherboard, fans, drives, RGB, etc.
        const totalWattage = cpuNeed + gpuNeed + overhead;
        const recommended = Math.ceil(totalWattage * 1.2 / 50) * 50; // round up to nearest 50W with 20% headroom
        const psuWattage = extractWattage(parts.psu);

        if (psuWattage !== null && psuWattage < totalWattage) {
            issues.push({
                type: 'critical',
                component: 'Power Supply', parts: ['psu'],
                issue: `Ang PSU na ito (${parts.psu.name}) ay ${psuWattage}W lang, pero kailangan ng build mo ng hindi bababa sa ${totalWattage}W — CPU: ~${cpuNeed}W, GPU: ~${gpuNeed}W, at ~${overhead}W para sa motherboard/fans/storage.`,
                solution: `Pumili ng PSU na ${recommended}W o mas mataas para may sapat na headroom.`
            });
        } else if (psuWattage !== null && psuWattage < recommended) {
            issues.push({
                type: 'warning',
                component: 'Power Supply', parts: ['psu'],
                issue: `Kaya na ng ${psuWattage}W PSU mo ang build (kailangan: ~${totalWattage}W), pero maliit lang ang extra headroom.`,
                solution: `Para mas matagal at ligtas ang PSU, mas maganda kung ${recommended}W o mas mataas (bahagi ito para sa power spikes at future upgrades).`
            });
        }
    }

    if (parts.case && parts.motherboard) {
        const sizeRank = { 'ATX': 3, 'mATX': 2, 'ITX': 1 };
        const caseFF = parts.case.formFactor || parts.case.form_factor;
        const moboFF = parts.motherboard.formFactor || parts.motherboard.form_factor;
        if (caseFF && moboFF && sizeRank[moboFF] > sizeRank[caseFF]) {
            issues.push({
                type: 'critical',
                component: 'Case/Motherboard', parts: ['case', 'motherboard'],
                issue: `Hindi kasya ang ${moboFF} na motherboard (${parts.motherboard.name}) sa ${caseFF} na case (${parts.case.name}) — mas malaki ang motherboard kaysa sa loob ng case.`,
                solution: `Pumili ng ${moboFF} o mas malaking case, o kaya ng ${caseFF}-size na motherboard.`
            });
        }
    }

    if (parts.cpu && parts.gpu) {
        const b = bottleneckIssue(parts.cpu, parts.gpu);
        if (b) issues.push(b);
    }

    const required = ['cpu', 'gpu', 'motherboard', 'ram', 'psu'];
    const missing = required.filter(r => !parts[r]);
    if (missing.length > 0) {
        issues.push({
            type: 'warning',
            component: 'Incomplete Build',
            issue: `Missing: ${missing.join(', ').toUpperCase()}`,
            solution: `Select all required components for a complete PC.`
        });
    }

    return issues;
}

function checkFullCompatibility() {
    compatibilityIssues = computeCompatIssues(customBuildComponents);
    renderCompatibilityReport();
    markCompatErrors();
}

// Turns the box of every part involved in a critical issue red, with the
// problem written inside it, so the customer sees at a glance what to change.
function markCompatErrors() {
    document.querySelectorAll('.component-selector[data-part]').forEach(card => {
        card.style.borderColor = '';
        card.style.boxShadow = '';
        card.style.background = '';
        const sel = card.querySelector('select.component-select');
        if (sel) sel.style.borderColor = '';
        card.querySelectorAll('.compat-error-note').forEach(n => n.remove());
    });
    compatibilityIssues.filter(i => i.type === 'critical' && i.parts).forEach(issue => {
        issue.parts.forEach(part => {
            const card = document.querySelector('.component-selector[data-part="' + part + '"]');
            if (!card) return;
            card.style.borderColor = '#FF5252';
            card.style.boxShadow = '0 0 0 1px #FF5252, 0 0 18px rgba(255, 82, 82, 0.25)';
            card.style.background = 'rgba(255, 82, 82, 0.06)';
            const sel = card.querySelector('select.component-select');
            if (sel) sel.style.borderColor = '#FF5252';
            const note = document.createElement('div');
            note.className = 'compat-empty compat-error-note';
            note.innerHTML = '<i class="fas fa-circle-xmark"></i> <strong>' + escapeHtml(issue.issue) + '</strong>'
                           + '<div style="margin-top:4px; color: var(--text-light);">💡 ' + escapeHtml(issue.solution) + '</div>';
            const header = card.querySelector('.selector-header');
            if (header) header.insertAdjacentElement('afterend', note); else card.prepend(note);
        });
    });
    updatePartsCompatBar();
}

// Green / amber / red bar above the parts list, like PCPartPicker's.
function updatePartsCompatBar() {
    const bar = document.getElementById('ppCompatBar');
    if (!bar) return;
    const picked = Object.values(customBuildComponents).some(c => c);
    const crit = compatibilityIssues.filter(i => i.type === 'critical').length;
    const warn = compatibilityIssues.filter(i => i.type === 'warning').length;
    let cls = 'ok', icon = 'fa-circle-check', text = 'No issues or incompatibilities found.';
    if (!picked) { cls = 'idle'; icon = 'fa-circle-info'; text = 'Choose your parts below to start.'; }
        else if (crit) { cls = 'bad'; icon = 'fa-circle-xmark'; text = crit + ' critical issue' + (crit > 1 ? 's' : '') + (warn ? ' and ' + warn + ' warning' + (warn > 1 ? 's' : '') : '') + ' found. See the parts marked in red.'; }
    else if (warn) { cls = 'warn'; icon = 'fa-triangle-exclamation'; text = warn + ' warning' + (warn > 1 ? 's' : '') + ' — the build works, but check the notes below.'; }
    bar.className = 'pp-compat pp-' + cls;
    bar.innerHTML = '<i class="fas ' + icon + '"></i> <strong>Compatibility:</strong> ' + text;
}

// Build Advice: a friendly message under the parts list that updates on its
// own every time a part changes — what's good, what's missing, and tips.
function renderCompatibilityReport() {
    const box = document.getElementById('compatibilityReport');
    if (!box) return;
    const cb = customBuildComponents;
    const picked = Object.values(cb).some(c => c);
    if (!picked) { box.innerHTML = ''; return; }

    const critical = compatibilityIssues.filter(i => i.type === 'critical');
    const warnings = compatibilityIssues.filter(i => i.type === 'warning' && i.component !== 'Incomplete Build');
    const required = { cpu: 'CPU', motherboard: 'motherboard', ram: 'memory', storage: 'storage', psu: 'power supply', case: 'case' };
    const igpu = cb.cpu && /\d{4,5}G\b|\d{4,5}GE?\b|APU/i.test(cb.cpu.name || '') && !/F\b/i.test(cb.cpu.name || '');
    if (!igpu) required.gpu = 'video card';
    const missing = Object.keys(required).filter(k => !cb[k]).map(k => required[k]);

    // What's already good
    const good = [];
    const ff = p => p && (p.formFactor || p.form_factor);
    if (cb.cpu && cb.motherboard && compatSocket(cb.cpu) && compatSocket(cb.cpu) === compatSocket(cb.motherboard))
        good.push(`Your CPU and motherboard both use the <strong>${escapeHtml(compatSocket(cb.cpu))}</strong> socket.`);
    if (cb.ram && cb.motherboard && compatRam(cb.ram) && compatRam(cb.ram) === compatRam(cb.motherboard)) {
        const gb = ['ram', ...RAM_EXTRA_SLOTS].reduce((n, k) => n + (cb[k] ? ramCapacityGB(cb[k]) : 0), 0);
        good.push(`<strong>${escapeHtml(compatRam(cb.ram))}</strong> memory matches your motherboard${gb ? ` — <strong>${gb}GB</strong> in total` : ''}.`);
    }
    if (cb.psu && (cb.cpu || cb.gpu) && !compatibilityIssues.some(i => i.component === 'Power Supply')) {
        const need = (cb.cpu ? (cb.cpu.tdp || 65) : 0) + (cb.gpu ? (cb.gpu.power || 200) : 0) + 50;
        const w = extractWattage(cb.psu);
        if (w) good.push(`The <strong>${w}W</strong> power supply has enough power (your build needs about ${need}W).`);
    }
    if (cb.case && cb.motherboard && ff(cb.case) && ff(cb.motherboard) && !critical.some(i => i.component === 'Case/Motherboard'))
        good.push(`The ${escapeHtml(ff(cb.motherboard))} motherboard fits inside the ${escapeHtml(ff(cb.case))} case.`);
    if (cb.cpu && !cb.gpu && igpu)
        good.push(`${escapeHtml(cb.cpu.name)} has built-in graphics, so a video card is optional.`);

    // Tips
    const tips = warnings.map(w => escapeHtml(w.issue) + (w.solution ? ` <em>${escapeHtml(w.solution)}</em>` : ''));
    const ramGB = ['ram', ...RAM_EXTRA_SLOTS].reduce((n, k) => n + (cb[k] ? ramCapacityGB(cb[k]) : 0), 0);
    if (cb.ram && ramGB && ramGB < 16) tips.push(`You have ${ramGB}GB of memory. <strong>16GB</strong> or more is better for gaming and multitasking.`);
    if (cb.storage && isHardDisk(cb.storage)) tips.push(`Your main drive is a hard disk. An <strong>SSD</strong> for Windows (C:) makes the PC start and load much faster.`);

    let headIcon, headText, cls;
    if (critical.length) { cls = 'bad'; headIcon = 'fa-circle-xmark'; headText = 'Some parts don’t work together yet'; }
    else if (missing.length) { cls = 'progress'; headIcon = 'fa-screwdriver-wrench'; headText = `Looking good so far — ${missing.length} more part${missing.length > 1 ? 's' : ''} to go`; }
    else if (tips.length) { cls = 'warn'; headIcon = 'fa-circle-check'; headText = 'Your build is complete and compatible — a few tips'; }
    else { cls = 'ok'; headIcon = 'fa-circle-check'; headText = 'Your build is complete and everything is compatible!'; }

    const list = (items, icon, kind) => items.length ? `<ul class="ba-list ba-${kind}">${items.map(t => `<li><i class="fas ${icon}"></i><span>${t}</span></li>`).join('')}</ul>` : '';
    box.innerHTML = `
        <div class="build-advice ba-${cls}">
            <div class="ba-head"><i class="fas fa-comment-dots"></i><div><small>Build Advice</small><strong><i class="fas ${headIcon}"></i> ${headText}</strong></div></div>
            ${list(critical.map(c => escapeHtml(c.issue) + (c.solution ? ` <em>${escapeHtml(c.solution)}</em>` : '')), 'fa-circle-xmark', 'bad')}
            ${missing.length ? `<p class="ba-missing"><i class="fas fa-list-check"></i> Still to choose: <strong>${missing.join(', ')}</strong>.</p>` : ''}
            ${list(good, 'fa-check', 'good')}
            ${list(tips, 'fa-lightbulb', 'tip')}
        </div>`;
}

function updateSummaryPanel() {
    const summaryPanel = document.getElementById('summaryPanel');
    if (!summaryPanel) return;
    // Advice is always live: re-check whenever the summary refreshes.
    compatChecked = true;
    checkFullCompatibility();

    let total = 0;
    let selectedCount = 0;
    
    for (const [key, component] of Object.entries(customBuildComponents)) {
        if (component) {
            total += component.price;
            selectedCount++;
        }
    }

    const criticalCount = compatibilityIssues.filter(i => i.type === 'critical').length;
    const unchecked = false;
    const isReady = selectedCount >= 5 && criticalCount === 0 && !unchecked;

    summaryPanel.innerHTML = `
        <h3><i class="fas fa-chart-simple"></i> Build Summary</h3>
        
        ${selectedStore === '__all__' ? `
            <div style="background: rgba(108,99,255,0.15); padding: 8px 12px; border-radius: 12px; margin-bottom: 16px; text-align: center; font-size: 0.85rem;">
                <i class="fas fa-globe"></i> Store: <strong>🌐 All Stores</strong>
                <button onclick="focusStoreSelect('customStoreGrid')" style="background: none; border: none; color: var(--primary); cursor: pointer;">(Change)</button>
            </div>
        ` : selectedStore && storesData[selectedStore] ? `
            <div style="background: rgba(108,99,255,0.15); padding: 8px 12px; border-radius: 12px; margin-bottom: 16px; text-align: center; font-size: 0.85rem;">
                <i class="fas fa-store"></i> Store: <strong>${storesData[selectedStore].name}</strong>
                <button onclick="focusStoreSelect('customStoreGrid')" style="background: none; border: none; color: var(--primary); cursor: pointer;">(Change)</button>
            </div>
        ` : ''}
        
        ${currentBrandFilter !== 'all' ? `
            <div style="background: rgba(108,99,255,0.15); padding: 8px 12px; border-radius: 12px; margin-bottom: 16px; text-align: center; font-size: 0.85rem;">
                <i class="fas fa-filter"></i> Brand: <strong>${getBrandDisplayName(currentBrandFilter)}</strong>
                <button onclick="setBrandFilter('all')" style="background: none; border: none; color: var(--primary); cursor: pointer;">(Clear)</button>
            </div>
        ` : ''}
        
        <div class="summary-stats">
            <div class="stat-row"><span>Components:</span><strong>${selectedCount}</strong></div>
            <div class="stat-row"><span>Total Price:</span><strong class="total-price-value">₱${total.toLocaleString()}</strong></div>
            <div class="stat-row">
                <span>Status:</span>
                ${unchecked ?
                    `<span style="color: var(--text-muted);"><i class="fas fa-circle-question"></i> Not checked yet</span>` :
                  criticalCount === 0 && selectedCount < 5 ?
                    `<span style="color: var(--accent);"><i class="fas fa-screwdriver-wrench"></i> In progress</span>` :
                  criticalCount === 0 ? 
                    `<span class="status-good"><i class="fas fa-check-circle"></i> Good to Go!</span>` : 
                    `<span class="status-bad"><i class="fas fa-times-circle"></i> ${criticalCount} Issue(s)</span>`
                }
            </div>
        </div>
        
        <div class="build-actions-summary">
            ${isReady ? 
                `<button class="btn-primary" onclick="saveCustomBuild()"><i class="fas fa-save"></i> Save Build</button>` :
                `<button class="btn-secondary" disabled style="opacity:0.5;">${criticalCount ? 'Fix Issues First' : 'Choose at least 5 parts'}</button>`
            }
        </div>
        
        <div class="build-tip">
            <i class="fas fa-lightbulb"></i>
            <span>💡 Tip: Balance your CPU and GPU for best gaming performance!</span>
        </div>
    `;
}

async function saveCustomBuild() {
    if (CHECK_ON_CLICK && !compatChecked) { compatChecked = true; checkFullCompatibility(); updateSummaryPanel(); }
    const criticalIssues = compatibilityIssues.filter(i => i.type === 'critical');
    if (criticalIssues.length > 0) {
        alert(`Cannot save due to ${criticalIssues.length} critical issue(s).`);
        return;
    }

    const selectedCount = Object.values(customBuildComponents).filter(c => c !== null).length;
    if (selectedCount < 5) {
        alert(`Please select at least 5 components.`);
        return;
    }

    if (!requireLoginToSave()) return;

    const name = prompt('Enter a name for your build:', 'My Custom PC Build');
    if (!name) return;

    const totalPrice = Object.values(customBuildComponents).reduce((sum, c) => sum + (c?.price || 0), 0);
    const buildToSave = {
        name: name,
        components: { ...customBuildComponents },
        totalPrice: totalPrice,
        savedAt: new Date().toLocaleDateString(),
        isCustom: true,
        ...buildStoreInfo(customBuildComponents)
    };

    await persistBuild(buildToSave);

    savedBuilds.unshift(buildToSave);
    localStorage.setItem('compumatch_builds', JSON.stringify(savedBuilds));
    alert('Build saved successfully!');
    showPage('mybuilds');
}

// Works out which store a custom build belongs to. In "All Stores" mode the
// parts can come from different shops, so the build has no single store.
function buildStoreInfo(components) {
    if (selectedStore && selectedStore !== '__all__' && storesData[selectedStore]) {
        return { storeId: selectedStore, storeName: storesData[selectedStore].name };
    }
    const ids = [...new Set(Object.values(components).filter(Boolean).map(c => c.storeId).filter(Boolean))];
    if (ids.length === 1 && storesData[ids[0]]) {
        return { storeId: ids[0], storeName: storesData[ids[0]].name };
    }
    return { storeId: null, storeName: ids.length > 1 ? 'Multiple stores' : null };
}

// Saves a build to the real database (if the client is logged in), and
// stamps the server-generated id onto the build object for later deletion.
async function persistBuild(build) {
    const email = getClientEmail();
    if (!email) return; // guest / not logged in — stays local-only for this browser

    const items = Object.entries(build.components)
        .filter(([slot, c]) => c)
        .map(([slot, c]) => ({ slot, item_id: c.id || null, item_name: c.name, item_price: c.price }));

    try {
        const res = await fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'save_build', email, name: build.name, store_id: build.storeId,
                total_price: build.totalPrice, items
            })
        });
        const data = await res.json();
        if (data.ok) build.id = data.build_id;
        else showToast(data.error || 'Could not save this build to your account.');
    } catch (e) {
        // Backend unreachable — keep working offline via localStorage only.
    }
}

function resetCustomBuild() {
    if (confirm('Reset all selected components?')) {
        customBuildComponents = { cpu: null, gpu: null, motherboard: null, ram: null, storage: null, storage2: null, storage3: null, storage4: null, storage5: null, storage6: null, storage7: null, storage8: null, storage9: null, nvme2: null, nvme3: null, nvme4: null, nvme5: null, nvme6: null, nvme7: null, ...emptyRamExtras(), psu: null, case: null };
        compatibilityIssues = [];
        compatChecked = false;
        currentBrandFilter = 'all';
        
        const brandCards = document.querySelectorAll('.brand-card');
        brandCards.forEach(card => {
            if (card.getAttribute('data-brand') === 'all') {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
        
        renderComponentSelectors();
        updateSummaryPanel();
    }
}

// =====================
// Generate Recommended Builds (using selected store's items)
// =====================
// =====================
// Recommendation profiles: how each intended use and performance level
// shapes the build (used by generateBuilds below).
// =====================
// Weights = how much each part matters for that use (they add up to 1).
// spend = how much of the budget range that use is worth spending
// (office PCs don't need the whole budget, gaming/creative can use it all).
const USE_PROFILES = {
    gaming:         { label: 'Gaming',               cpu: 0.30, gpu: 0.55, ram: 0.10, storage: 0.05, minRamGB: 16, spend: 1.0, why: 'Most of the budget goes to the graphics card for high FPS.' },
    office:         { label: 'Office/School',        cpu: 0.45, gpu: 0.05, ram: 0.30, storage: 0.20, minRamGB: 8,  spend: 0.35, why: 'Keeps the graphics card basic and the total low — plenty for documents and browsing.' },
    creative:       { label: 'Creative/Media',       cpu: 0.40, gpu: 0.30, ram: 0.20, storage: 0.10, minRamGB: 32, spend: 1.0, why: 'Strong CPU and more RAM for editing and rendering, with a capable GPU.' },
    business:       { label: 'Business',             cpu: 0.45, gpu: 0.10, ram: 0.30, storage: 0.15, minRamGB: 16, spend: 0.6, why: 'Favors CPU and RAM for multitasking; the GPU stays modest.' },
    networking:     { label: 'Networking/Server',    cpu: 0.35, gpu: 0.00, ram: 0.25, storage: 0.40, minRamGB: 16, spend: 0.8, why: 'Puts the money into storage and RAM; the GPU is kept to the minimum.' },
    virtualization: { label: 'Virtualization/Labs',  cpu: 0.45, gpu: 0.05, ram: 0.40, storage: 0.10, minRamGB: 32, spend: 0.9, why: 'More CPU power and as much RAM as possible to run several VMs at once.' }
};
// Where in the budget range each performance level aims (0 = minimum, 1 = maximum).
const PERF_TARGETS = {
    entry:      { label: 'Entry Level',      at: 0.10 },
    balanced:   { label: 'Balanced',         at: 0.50 },
    high:       { label: 'High Performance', at: 0.80 },
    enthusiast: { label: 'Enthusiast',       at: 1.00 }
};

// Reads sizes out of product names/specs, e.g. "32GB (2x16)", "2x8GB", "1TB", "500GB".
function ramCapacityGB(item) {
    const t = ((item && item.name) || '') + ' ' + ((item && item.specs) || '');
    let m = t.match(/(\d+)\s*[xX×]\s*(\d+)\s*GB/);
    if (m) return +m[1] * +m[2];
    m = t.match(/(\d+)\s*GB/i);
    return m ? +m[1] : 0;
}
function storageCapacityGB(item) {
    const t = ((item && item.name) || '') + ' ' + ((item && item.specs) || '');
    let m = t.match(/(\d+(?:\.\d+)?)\s*TB/i);
    if (m) return Math.round(parseFloat(m[1]) * 1000);
    m = t.match(/(\d{3,4})\s*GB/i);
    return m ? +m[1] : 0;
}
function isHardDisk(item) {
    const t = (((item && item.name) || '') + ' ' + ((item && item.specs) || '') + ' ' + ((item && item.storage_type) || '')).toUpperCase();
    return /\bHDD\b|HARD DRIVE|\d{4}\s*RPM/.test(t);
}
function cpuCoreCount(item) {
    const t = ((item && item.name) || '') + ' ' + ((item && item.specs) || '');
    const m = t.match(/(\d+)\s*[- ]?(?:cores?|C\/)/i);
    return m ? +m[1] : 0;
}

function generateBuilds() {
    const minBudget = parseInt(document.getElementById('budgetMinSlider')?.value) || 15000;
    const maxBudget = parseInt(document.getElementById('budgetMaxSlider')?.value) || 200000;
    const use  = document.querySelector('#useGroup .radio-card.selected')?.dataset.value || 'gaming';
    const perf = document.querySelector('#perfGroup .radio-card.selected')?.dataset.value || 'balanced';

    // Pool every store's inventory together, tagging each item with which
    // store it's actually available from (so the shopping list stays accurate).
    const pool = [];
    for (const storeId in storesData) {
        const store = storesData[storeId];
        (store.inventory || []).forEach(item => {
            pool.push({ ...item, isStoreItem: true, storeId, storeName: store.name });
        });
    }

    // Many stores often sell the same model. For recommendations only the
    // cheapest in-stock listing of each model matters, so collapse duplicates
    // first — this keeps the search fast even with dozens of stores.
    const modelKey = i => (String(i.name || '').toLowerCase().replace(/\s+/g, ' ').trim()) + '|' + (i.socket || '') + '|' + (i.ram_type || '');
    function cheapestPerModel(list) {
        const best = new Map();
        list.forEach(i => {
            const k = modelKey(i), cur = best.get(k);
            if (!cur || i.price < cur.price || (i.price === cur.price && (i.stock || 0) > (cur.stock || 0))) best.set(k, i);
        });
        return [...best.values()].sort((a, b) => a.price - b.price);
    }

    // Among equally strong parts, a pricier one is never the better pick, so
    // only the few cheapest per performance level are tried. This caps the
    // number of CPU x GPU pairs no matter how many stores and models exist.
    function cheapestPerTier(list, kind, keep) {
        const groups = new Map();
        list.forEach(i => {
            const t = (performanceTier(i, kind) || { tier: 0 }).tier;
            const k = kind === 'cpu' ? t + '|' + (compatSocket(i) || '') + '|' + cpuCoreCount(i) : String(t);
            if (!groups.has(k)) groups.set(k, []);
            const g = groups.get(k);
            if (g.length < keep) g.push(i);            // list is cheapest-first
        });
        return [].concat(...groups.values()).sort((a, b) => a.price - b.price);
    }

    const cpus = cheapestPerTier(cheapestPerModel(pool.filter(i => i.category === 'cpu' && i.stock > 0)), 'cpu', 3);
    const gpus = cheapestPerTier(cheapestPerModel(pool.filter(i => i.category === 'gpu' && i.stock > 0)), 'gpu', 3);

    if (cpus.length === 0 || gpus.length === 0) {
        alert(`None of our partner stores have enough components in stock to generate a build right now.`);
        return;
    }

    const inStock = cat => cheapestPerModel(pool.filter(i => i.category === cat && i.stock > 0));
    const mobos = inStock('motherboard'), rams = inStock('ram'), storages = inStock('storage'),
          psus = inStock('psu'), cases = inStock('case');
    const asParts = b => ({ cpu: b.cpu, gpu: b.gpu, motherboard: b.mobo, ram: b.ram, storage: b.storage, storage2: null, psu: b.psu, case: b.pcCase });
    const hasCritical = b => computeCompatIssues(asParts(b)).some(i => i.type === 'critical');

    const profile = USE_PROFILES[use] || USE_PROFILES.gaming;
    const perfT = PERF_TARGETS[perf] || PERF_TARGETS.balanced;
    const wantsMoreRam = profile.ram >= 0.2 || perf === 'high' || perf === 'enthusiast';

    // RAM: smallest kit that meets the use's minimum (cheapest first); uses that
    // lean on memory (creative, VMs…) or high performance levels take the
    // biggest kit up to twice that minimum instead.
    function pickRam(list) {
        if (!list.length) return null;
        const enough = list.filter(r => ramCapacityGB(r) >= profile.minRamGB);
        if (!enough.length) return list.slice().sort((a, b) => ramCapacityGB(b) - ramCapacityGB(a) || a.price - b.price)[0];
        if (!wantsMoreRam) return enough[0]; // list is already cheapest-first
        const cap = profile.minRamGB * 2;
        return enough.slice().sort((a, b) =>
            Math.min(ramCapacityGB(b), cap) - Math.min(ramCapacityGB(a), cap) || a.price - b.price)[0];
    }

    // Main drive: an SSD (never a hard disk when an SSD is available). Storage-
    // heavy uses get the biggest SSD; others the cheapest one of 500GB or more.
    const ssds = storages.filter(st => !isHardDisk(st));
    const mainPool = ssds.length ? ssds : storages;
    let mainStorage = null;
    if (mainPool.length) {
        if (profile.storage >= 0.25) {
            mainStorage = mainPool.slice().sort((a, b) => storageCapacityGB(b) - storageCapacityGB(a) || a.price - b.price)[0];
        } else {
            mainStorage = mainPool.find(st => storageCapacityGB(st) >= 500) || mainPool[0];
        }
    }

    // Assemble a full build around a chosen CPU + GPU from ANY store in the
    // pool, cheapest matching part first. Returns null when no fully
    // compatible build exists for that CPU — a recommended build must never
    // fail the Check Compatibility button.
    // Motherboard + RAM options per CPU socket, worked out once per socket
    // instead of once per CPU/GPU pair.
    const platformCache = new Map();
    function platformsFor(sock) {
        if (platformCache.has(sock)) return platformCache.get(sock);
        const list = [];
        for (const mobo of mobos) {
            if (compatSocket(mobo) !== sock) continue;
            const ramType = compatRam(mobo);
            if (!ramType) continue;                      // board's RAM type unknown -> can't vouch for it
            const ram = pickRam(rams.filter(r => compatRam(r) === ramType));
            if (ram) list.push({ mobo, ram });
        }
        platformCache.set(sock, list);
        return list;
    }

    function assemble(cpu, gpu) {
        const sock = compatSocket(cpu);
        if (!sock) return null;                          // can't vouch for a CPU whose socket is unknown

        for (const { mobo, ram } of platformsFor(sock)) {

            const storage = mainStorage;
            if (!storage) continue;

            // PSU: enough for the build with headroom if possible, else just enough.
            const need = (cpu.tdp || 65) + (gpu.power || 200) + 50;
            const comfy = Math.ceil(need * 1.2 / 50) * 50;
            const psu = psus.find(p => (extractWattage(p) || 0) >= comfy)
                     || psus.find(p => (extractWattage(p) || 0) >= need);
            if (!psu) continue;

            // Case: the first one this exact combination passes the rules with.
            for (const pcCase of cases) {
                const build = { cpu, gpu, mobo, ram, storage, psu, pcCase };
                if (hasCritical(build)) continue;
                build.price = cpu.price + gpu.price + mobo.price + ram.price + storage.price + psu.price + pcCase.price;
                return build;
            }
        }
        return null;
    }

    // Build every CPU x GPU combination that can be made fully compatible
    let candidates = [];
    cpus.forEach(cpu => gpus.forEach(gpu => { const b = assemble(cpu, gpu); if (b) candidates.push(b); }));

    // Gaming needs a CPU and GPU that match; drop pairs that would bottleneck
    // (unless that leaves nothing). Other uses (editing, office, servers, VMs)
    // benefit from a strong CPU even next to a modest GPU, so they keep them.
    if (profile.gpu >= 0.5) {
        const balancedPairs = candidates.filter(b => !bottleneckIssue(b.cpu, b.gpu));
        if (balancedPairs.length) candidates = balancedPairs;
    }

    if (candidates.length === 0) {
        alert('We could not put together a fully compatible build from the parts currently in stock. Try again later, or use Custom Build to pick parts yourself.');
        return;
    }

    // ---- Score every candidate for the chosen use + performance level ----
    // Part strength is scored 0..1 against the strongest candidate:
    //  CPU = performance tier (and core count when listed), GPU = tier,
    //  RAM = capacity, storage = capacity.
    const tierOf = (item, kind) => (performanceTier(item, kind) || { tier: 1 }).tier;
    const maxOf = f => candidates.reduce((m, b) => Math.max(m, f(b)), 1);
    const maxCores = maxOf(b => cpuCoreCount(b.cpu));
    const maxRam = maxOf(b => ramCapacityGB(b.ram));
    const maxSto = maxOf(b => storageCapacityGB(b.storage));
    const range = Math.max(1, maxBudget - minBudget);
    // Price point to aim for: the performance level's spot in the range,
    // scaled by how much the chosen use is worth spending.
    const target = minBudget + perfT.at * (profile.spend || 1) * (maxBudget - minBudget);

    candidates.forEach(b => {
        const cores = cpuCoreCount(b.cpu);
        const cpuS = cores ? 0.6 * (tierOf(b.cpu, 'cpu') / 5) + 0.4 * (cores / maxCores) : tierOf(b.cpu, 'cpu') / 5;
        const gpuS = tierOf(b.gpu, 'gpu') / 5;
        const ramS = ramCapacityGB(b.ram) / maxRam;
        const stoS = storageCapacityGB(b.storage) / maxSto;
        // How well the parts suit the use (0..1)
        b.fit = profile.cpu * cpuS + profile.gpu * gpuS + profile.ram * ramS + profile.storage * stoS;
        // Money spent on a GPU the use doesn't need is wasted budget
        const gpuShare = b.gpu.price / b.price;
        b.waste = profile.gpu < 0.15 ? gpuShare * (0.15 - profile.gpu) * 4 : 0;
        // Closeness to the price point of the chosen performance level
        b.distance = Math.abs(b.price - target) / range;
        b.score = b.fit - b.waste - 1.0 * b.distance;
    });

    // Keep only builds WITHIN the budget range, best score first, one per CPU+GPU pair
    const seen = new Set();
    let inRange = candidates
        .filter(b => b.price >= minBudget && b.price <= maxBudget)
        .sort((a, b) => b.score - a.score)
        .filter(b => {
            const sig = b.cpu.name + '|' + b.gpu.name;
            if (seen.has(sig)) return false;
            seen.add(sig);
            return true;
        })
        .slice(0, 6);

    // Summarize which store(s) a build's parts are coming from
    function storeSummary(b) {
        const names = [...new Set([b.cpu, b.gpu, b.mobo, b.ram, b.storage, b.psu, b.pcCase]
            .map(c => c.storeName).filter(Boolean))];
        if (names.length === 0) return 'Partner Stores';
        if (names.length === 1) return names[0];
        return `${names.length} stores: ${names.join(', ')}`;
    }

    function toBuild(b, i) {
        return {
            name: `Build ${i + 1} • ₱${b.price.toLocaleString()}`,
            label: `Build ${i + 1}`,
            best: i === 0,
            price: b.price,
            tag: profile.label + ' • ' + perfT.label,
            useLabel: profile.label,
            perfLabel: perfT.label,
            why: profile.why,
            stats: [
                { k: 'CPU', v: '₱' + b.cpu.price.toLocaleString() },
                { k: 'GPU', v: '₱' + b.gpu.price.toLocaleString(), sub: Math.round(b.gpu.price / b.price * 100) + '% share' },
                { k: 'RAM', v: (ramCapacityGB(b.ram) || '?') + 'GB' },
                { k: 'Storage', v: storageCapacityGB(b.storage) >= 1000 ? (storageCapacityGB(b.storage) / 1000) + 'TB' : (storageCapacityGB(b.storage) || '?') + 'GB' }
            ],
            stores: [...new Set([b.cpu, b.gpu, b.mobo, b.ram, b.storage, b.psu, b.pcCase].map(c => c && c.storeName).filter(Boolean))],
            reason: (i === 0 ? '⭐ Best match. ' : '') + profile.why
                + ` CPU ₱${b.cpu.price.toLocaleString()} · GPU ₱${b.gpu.price.toLocaleString()} (${Math.round(b.gpu.price / b.price * 100)}% of the total) · ${ramCapacityGB(b.ram) || '?'}GB RAM · ${storageCapacityGB(b.storage) >= 1000 ? (storageCapacityGB(b.storage) / 1000) + 'TB' : (storageCapacityGB(b.storage) || '?') + 'GB'} storage.`,
            storeName: storeSummary(b),
            storeId: null, // parts may come from multiple stores — see per-component storeName instead
            components: {
                cpu: b.cpu,
                gpu: b.gpu,
                motherboard: b.mobo,
                ram: b.ram,
                storage: b.storage,
                storage2: null, storage3: null, storage4: null, storage5: null, storage6: null, storage7: null, storage8: null, storage9: null, nvme2: null, nvme3: null, nvme4: null, nvme5: null, nvme6: null, nvme7: null, ...emptyRamExtras(),
                psu: b.psu,
                case: b.pcCase
            },
            // Keep the old name-only shape too, for anything still reading .components as text
            componentNames: {
                cpu: b.cpu.name,
                gpu: b.gpu.name,
                motherboard: b.mobo.name,
                ram: b.ram.name,
                storage: b.storage.name,
                psu: b.psu.name,
                case: b.pcCase.name
            }
        };
    }

    let builds, note, noteHtml = '';
    if (inRange.length > 0) {
        builds = inRange.map(toBuild);
        note = `Showing ${builds.length} ${profile.label} build${builds.length > 1 ? 's' : ''} (${perfT.label}) within ₱${minBudget.toLocaleString()} – ₱${maxBudget.toLocaleString()}, best match first, mixed across all partner stores.`;
        noteHtml = `Showing <span class="hl">${builds.length} ${escapeHtml(profile.label)} build${builds.length > 1 ? 's' : ''}</span>`
            + ` at <span class="hl">${escapeHtml(perfT.label)}</span> performance within <span class="hl hl-money">₱${minBudget.toLocaleString()} – ₱${maxBudget.toLocaleString()}</span>.`
            + `<small>⭐ Best match is first · parts mixed across all partner stores · tap a build to see its parts</small>`;
    } else {
        // Nothing fits exactly — show the builds closest to the performance
        // level's price point, best suited first among them
        const closest = candidates
            .slice()
            .sort((a, b) => Math.abs(a.price - target) - Math.abs(b.price - target))
            .slice(0, 6)
            .sort((a, b) => b.score - a.score)
            .slice(0, 3);
        builds = closest.map(toBuild);
        note = `No builds fall exactly within ₱${minBudget.toLocaleString()} – ₱${maxBudget.toLocaleString()}. Showing the closest options instead.`;
        noteHtml = `<span class="hl hl-warn">No builds fall exactly within ₱${minBudget.toLocaleString()} – ₱${maxBudget.toLocaleString()}.</span>`
            + `<small>Showing the closest options instead · tap a build to see its parts</small>`;
    }

    localStorage.setItem('generatedBuilds', JSON.stringify(builds));
    localStorage.setItem('buildsNote', note);
    localStorage.setItem('buildsNoteHtml', noteHtml);
    localStorage.setItem('buildsRange', JSON.stringify({ min: minBudget, max: maxBudget }));
    showPage('results');
}

function renderBuilds() {
    const builds = JSON.parse(localStorage.getItem('generatedBuilds') || '[]');
    const container = document.getElementById('buildsGrid');
    const noteEl = document.getElementById('buildsNote');
    const subtitle = document.getElementById('resultsSubtitle');

    // Show the selected budget range in the subtitle
    const range = JSON.parse(localStorage.getItem('buildsRange') || 'null');
    if (subtitle && range) {
        subtitle.textContent = `Budget range: ₱${range.min.toLocaleString()} – ₱${range.max.toLocaleString()}`;
    }

    // Show the note banner (highlighted version when available)
    const note = localStorage.getItem('buildsNote') || '';
    const noteHtml = localStorage.getItem('buildsNoteHtml') || '';
    if (noteEl) {
        if (noteHtml) noteEl.innerHTML = noteHtml; else noteEl.textContent = note;
        noteEl.style.display = (noteHtml || note) ? 'block' : 'none';
    }

    if (!builds.length) { container.innerHTML = '<p style="text-align:center;color:var(--text-muted);">No builds found for this range. Try widening your budget.</p>'; return; }

    const PART_ROWS = [['cpu', 'CPU'], ['gpu', 'GPU'], ['motherboard', 'Motherboard'], ['ram', 'RAM'], ['storage', 'Storage'], ['psu', 'PSU'], ['case', 'Case']];

    container.innerHTML = builds.map((build, i) => {
        const c = build.components || {};
        const label = build.label || `Build ${i + 1}`;
        const best = build.best || i === 0;
        const stores = build.stores || [];
        const statsHtml = (build.stats || []).map(st =>
            `<span class="rb-stat"><em>${escapeHtml(st.k)}</em><strong>${escapeHtml(st.v)}</strong>${st.sub ? `<small>${escapeHtml(st.sub)}</small>` : ''}</span>`).join('');
        const partsHtml = PART_ROWS.filter(([k]) => c[k]).map(([k, name]) => `
            <div class="rb-part">
                <span class="rb-part-label">${name}</span>
                <span class="rb-part-name">${escapeHtml(ppDisplayName({ ...c[k], brandName: c[k].brandName || c[k].brand }))}
                    ${c[k].storeName ? `<span class="rb-part-store"><i class="fas fa-store"></i> ${escapeHtml(c[k].storeName)}</span>` : ''}</span>
                ${c[k].price ? `<span class="rb-part-price">₱${Number(c[k].price).toLocaleString()}</span>` : ''}
            </div>`).join('');
        return `
        <div class="build-card rb-card${best ? ' rb-best' : ''}">
            <div class="build-header">
                <div>
                    <h3 class="rb-title">${escapeHtml(label)}</h3>
                    ${best ? '<span class="rb-badge">⭐ Best match</span>' : ''}
                </div>
                <span class="build-price">₱${build.price.toLocaleString()}</span>
            </div>
            <div class="rb-chips">
                ${build.useLabel ? `<span class="rb-chip">${escapeHtml(build.useLabel)}</span><span class="rb-chip rb-chip-alt">${escapeHtml(build.perfLabel || '')}</span>` : `<span class="rb-chip">${escapeHtml(build.tag || '')}</span>`}
            </div>
            ${statsHtml ? `<div class="rb-stats">${statsHtml}</div>` : ''}
            <div class="rb-stores"><i class="fas fa-store"></i> ${stores.length ? stores.map(n => `<strong>${escapeHtml(n)}</strong>`).join(', ') : escapeHtml(build.storeName || 'Partner Stores')}</div>
            <details class="rb-details">
                <summary><span class="rb-open">Show parts &amp; details</span><span class="rb-close">Hide parts</span><i class="fas fa-chevron-down"></i></summary>
                ${build.why ? `<div class="build-reason">${escapeHtml(build.why)}</div>` : (build.reason ? `<div class="build-reason">${escapeHtml(build.reason)}</div>` : '')}
                <div class="rb-parts">${partsHtml}</div>
            </details>
            <div class="build-actions">
                <button class="btn-primary" data-name="${escapeHtml(build.name)}" onclick="useRecommendedBuild(this.dataset.name)">Use This Build</button>
            </div>
        </div>`;
    }).join('');
}

function useRecommendedBuild(buildName) {
    const builds = JSON.parse(localStorage.getItem('generatedBuilds') || '[]');
    const build = builds.find(b => b.name === buildName);
    if (build) {
        // Multi-store builds don't map to a single store — fall back to
        // whichever store the CPU came from so Custom Build has something
        // to filter by; the person can still swap any part freely after.
        const cpuStoreId = build.components?.cpu?.storeId;
        const partStores = new Set(Object.values(build.components || {}).filter(c => c && c.storeId).map(c => c.storeId));
        if (partStores.size > 1) {
            // Parts from several stores: All Stores is the only view that lists them all.
            selectedStore = '__all__';
        } else if (build.storeId && storesData[build.storeId]) {
            selectedStore = build.storeId;
        } else if (cpuStoreId && storesData[cpuStoreId]) {
            selectedStore = cpuStoreId;
        } else {
            for (const storeId in storesData) {
                if (storesData[storeId].name === build.storeName) {
                    selectedStore = storeId;
                    break;
                }
            }
        }

        customBuildComponents = {
            cpu: null, gpu: null, motherboard: null, ram: null,
            storage: null, storage2: null, storage3: null, storage4: null, storage5: null, storage6: null, storage7: null, storage8: null, storage9: null, nvme2: null, nvme3: null, nvme4: null, nvme5: null, nvme6: null, nvme7: null, ...emptyRamExtras(), psu: null, case: null,
            ...rehydrateComponents(build.components)
        };
        compatibilityIssues = [];
        compatChecked = false;
        currentBrandFilter = 'all';

        showPage('custom');
        setTimeout(() => {
            renderStoreSelection();
            if (selectedStore) {
                document.getElementById('customSelectedStoreWarning').style.display = 'none';
                document.getElementById('customBuildContent').style.display = 'block';
            }
            renderComponentSelectors();
            updateSummaryPanel();
            const multiStore = build.storeName && build.storeName.includes(' stores:');
            showToast(multiStore
                ? `"${build.name}" loaded — parts came from multiple stores, so double-check availability per store before buying.`
                : `"${build.name}" loaded — feel free to swap any part.`);
        }, 100);
    }
}

// =====================
// Saved Builds with PDF Download
// =====================
function renderSavedBuilds() {
    const container = document.getElementById('savedBuildsGrid');
    const emptyMsg = document.getElementById('emptyBuildsMsg');
    const guestMsg = document.getElementById('guestBuildsMsg');

    // Builds are tied to an account — a guest shouldn't see whatever happens
    // to be cached in this browser's localStorage (could be stale data from
    // before login was required, or another person's builds on a shared PC).
    if (!getClientEmail()) {
        if (container) container.innerHTML = '';
        if (emptyMsg) emptyMsg.style.display = 'none';
        if (guestMsg) guestMsg.style.display = 'block';
        return;
    }
    if (guestMsg) guestMsg.style.display = 'none';

    if (!savedBuilds.length) {
        if (container) container.innerHTML = '';
        if (emptyMsg) emptyMsg.style.display = 'block';
        return;
    }
    if (emptyMsg) emptyMsg.style.display = 'none';
    container.innerHTML = savedBuilds.map((build, idx) => `
        <div class="build-card">
            <h3>${escapeHtml(build.name)}</h3>
            <div class="build-price">₱${(build.totalPrice || 0).toLocaleString()}</div>
            ${build.storeName ? `<p style="color: var(--primary); font-size: 0.8rem;"><i class="fas fa-store"></i> ${build.storeName}</p>` : ''}
            <p style="color: var(--text-muted); font-size: 0.8rem;">📅 Saved: ${build.savedAt || 'Today'}</p>
            <div class="build-actions" style="margin-top: 16px;">
                <button class="btn-secondary" onclick="loadCustomBuild(${idx})">📂 Load & Edit</button>
                <button class="btn-secondary" onclick="viewSavedBuildIn3D(${idx})">🧊 View in Simulator</button>
                <button class="btn-primary" onclick="downloadBuildAsPDF(savedBuilds[${idx}], ${idx})">📄 Download PDF</button>
                <button class="btn-outline" onclick="deleteBuild(${idx})">🗑️ Delete</button>
            </div>
        </div>
    `).join('');
}

// Builds saved to the database only keep each part's id, name and price.
// Before editing, swap each part for the store's current item (photo, specs,
// stock, socket, SATA ports…) so the editor and compatibility check have
// everything. A part the store no longer sells is kept as it was saved.
function rehydrateComponents(components) {
    const out = {};
    for (const [slot, c] of Object.entries(components || {})) {
        if (!c) { out[slot] = c; continue; }
        let found = null, foundStore = null;
        const tryStore = sid => {
            const it = sid && storesData[sid] && (storesData[sid].inventory || []).find(i => i.id === c.id);
            if (it) { found = it; foundStore = sid; }
        };
        if (c.id) {
            tryStore(c.storeId);
            if (!found) for (const sid in storesData) { tryStore(sid); if (found) break; }
        }
        out[slot] = found ? {
            ...c, ...found,
            isStoreItem: true,
            storeId: foundStore,
            storeName: storesData[foundStore].name,
            brandName: found.brand
        } : c;
    }
    return out;
}

// My Builds → open a saved build straight in the 3D Simulator.
function viewSavedBuildIn3D(index) {
    const build = savedBuilds[index];
    if (!build || !build.components) return;
    customBuildComponents = {
        cpu: null, gpu: null, motherboard: null, ram: null,
        storage: null, storage2: null, storage3: null, storage4: null, storage5: null, storage6: null, storage7: null, storage8: null, storage9: null,
        nvme2: null, nvme3: null, nvme4: null, nvme5: null, nvme6: null, nvme7: null, ...emptyRamExtras(), psu: null, case: null,
        ...rehydrateComponents(build.components)
    };
    compatibilityIssues = [];
    compatChecked = false;
    if (typeof viewBuildIn3D === 'function') viewBuildIn3D();
}

function loadCustomBuild(index) {
    const build = savedBuilds[index];
    if (build.isCustom && build.components) {
        customBuildComponents = { ...rehydrateComponents(build.components) };
        const partStores = new Set(Object.values(build.components).filter(c => c && c.storeId).map(c => c.storeId));
        if (partStores.size > 1 || (!build.storeId && build.storeName === 'Multiple stores')) {
            selectedStore = '__all__'; // parts from several stores — only All Stores lists them all
        } else if (build.storeId) {
            selectedStore = build.storeId;
        } else if (partStores.size === 1) {
            selectedStore = [...partStores][0];
        }
        compatibilityIssues = [];
        compatChecked = false;
        currentBrandFilter = 'all';
        showPage('custom');
        setTimeout(() => {
            renderStoreSelection();
            if (selectedStore) {
                document.getElementById('customSelectedStoreWarning').style.display = 'none';
                document.getElementById('customBuildContent').style.display = 'block';
            }
            renderComponentSelectors();
            updateSummaryPanel();
        }, 100);
    }
}

// Pulls this client's saved builds from the database so they show up on
// any device they log in from, not just the browser that saved them.
async function syncBuildsFromBackend() {
    const email = getClientEmail();
    if (!email) return;
    try {
        const res = await fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'list_builds', email })
        });
        const data = await res.json();
        if (data.ok && Array.isArray(data.builds)) {
            savedBuilds = data.builds.map(b => ({
                id: b.id,
                name: b.name,
                totalPrice: b.totalPrice,
                isCustom: b.isCustom,
                storeId: b.storeId,
                storeName: b.storeName,
                savedAt: b.savedAt ? new Date(b.savedAt).toLocaleDateString() : '',
                components: Object.fromEntries(
                    Object.entries(b.components || {}).map(([slot, c]) => [slot, { id: c.id, name: c.name, price: c.price }])
                )
            }));
            localStorage.setItem('compumatch_builds', JSON.stringify(savedBuilds));
            renderSavedBuilds();
        }
    } catch (e) {
        // Backend unreachable — keep showing whatever's cached locally.
    }
}

async function deleteBuild(index) {
    if (confirm('Delete this build?')) {
        const build = savedBuilds[index];
        const email = getClientEmail();
        if (build && build.id && email) {
            try {
                const res = await fetch('api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete_build', email, build_id: build.id })
                });
                await res.json();
            } catch (e) {
                // Backend unreachable — still remove locally so offline mode keeps working.
            }
        }
        savedBuilds.splice(index, 1);
        localStorage.setItem('compumatch_builds', JSON.stringify(savedBuilds));
        renderSavedBuilds();
    }
}

// =====================
// Browse Parts
// =====================
function clearBrowseSearch() {
    const input = document.getElementById('browseSearch');
    if (input) input.value = '';
    const clearBtn = document.getElementById('browseSearchClear');
    if (clearBtn) clearBtn.style.display = 'none';
    renderBrowseParts();
}

function renderBrowseParts() {
    const selectedCat = document.querySelector('#partCategoryFilter .radio-card.selected')?.dataset.cat || 'all';
    const selectedBrand = document.getElementById('browseBrandFilter')?.value || 'all';
    
    let allParts = [];
    
    // Add store items based on selected store
    if (selectedStore === null || selectedStore === '__all__') {
        // Show all stores
        for (const storeId in storesData) {
            const store = storesData[storeId];
            store.inventory.forEach(item => {
                if (true) {   // category is filtered below, after the counts
                    allParts.push({
                        ...item,
                        source: 'store',
                        storeName: store.name,
                        storeId: store.id
                    });
                }
            });
        }
    } else {
        // Show only selected store
        const store = storesData[selectedStore];
        if (store) {
            store.inventory.forEach(item => {
                if (true) {   // category is filtered below, after the counts
                    allParts.push({
                        ...item,
                        source: 'store',
                        storeName: store.name,
                        storeId: store.id
                    });
                }
            });
        }
    }
    
    if (selectedBrand !== 'all') {
        allParts = allParts.filter(part => part.brand?.toLowerCase() === selectedBrand);
    }

    // Search filter — matches name, brand, specs, and category (all words must match)
    const query = (document.getElementById('browseSearch')?.value || '').trim().toLowerCase();
    if (query) {
        const terms = query.split(/\s+/);
        allParts = allParts.filter(part => {
            const hay = `${part.brandName || part.brand || ''} ${part.name || ''} ${part.specs || ''} ${part.category || ''}`.toLowerCase();
            return terms.every(t => hay.includes(t));
        });
    }
    
    // Counts on the category pills (for the current store / brand / search)
    const catCounts = { all: allParts.length };
    allParts.forEach(p => { catCounts[p.category] = (catCounts[p.category] || 0) + 1; });
    document.querySelectorAll('#partCategoryFilter .radio-card').forEach(card => {
        const el = card.querySelector('.bp-count');
        if (el) el.textContent = catCounts[card.dataset.cat] || 0;
    });
    if (selectedCat !== 'all') allParts = allParts.filter(p => p.category === selectedCat);

    const container = document.getElementById('partsGrid');
    if (allParts.length === 0) {
        container.innerHTML = query ? `
            <div class="no-items-message">
                <i class="fas fa-magnifying-glass"></i>
                <h3>No parts match “${escapeHtml(query)}”</h3>
                <p>Try a different keyword, or clear the search to see all parts.</p>
            </div>
        ` : `
            <div class="no-items-message">
                <i class="fas fa-box-open"></i>
                <h3>No parts found</h3>
                <p>${selectedStore ? escapeHtml(storesData[selectedStore]?.name) : 'No store'} doesn't have any ${escapeHtml(selectedCat !== 'all' ? selectedCat : '')} components available.</p>
            </div>
        `;
    } else {
        browsePartsShown = allParts;
        container.innerHTML = allParts.map((part, idx) => `
            <div class="component-card part-clickable" style="display: flex; align-items: center; gap: 14px;" role="button" tabindex="0"
                 title="View details" onclick="openPartDetails(${idx})" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();openPartDetails(${idx});}">
                ${part.image ? `<img src="${escapeHtml(part.image)}" alt="${escapeHtml(part.name)}" loading="lazy" class="part-thumb">` : `<span class="part-thumb part-thumb-empty"><i class="fas fa-microchip"></i></span>`}
                <div style="flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 6px;">
                    <div style="display: flex; justify-content: space-between; gap: 12px; width: 100%;">
                        <strong class="part-name">${escapeHtml(ppDisplayName({ ...part, brandName: part.brandName || part.brand }))}</strong>
                        <span style="color: var(--primary); white-space: nowrap;">₱${part.price.toLocaleString()}</span>
                    </div>
                    <div class="part-specs-clamp">${escapeHtml(getPartSpecs(part))}</div>
                    <div style="display: flex; justify-content: space-between; width: 100%;">
                        <div style="font-size:0.7rem; color: var(--accent);">
                            <i class="fas fa-store"></i> ${escapeHtml(part.storeName)}
                        </div>
                        <div style="font-size:0.7rem; color: ${part.stock > 0 ? 'var(--success)' : 'var(--warning)'};">
                            ${part.stock > 0 ? `${part.stock} in stock` : 'Out of stock'}
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }
}

// =====================
// Part details pop-up (Browse Parts): big photo + every detail we have
// =====================
let browsePartsShown = [];

function openPartDetails(idx) {
    const part = browsePartsShown[idx];
    if (!part) return;
    openPartDetailsFor(part, {
        addLabel: 'Add to Custom Build',
        canAdd: part.stock > 0,
        onAdd: `addPartToCustomBuild(${idx})`
    });
}

// opts: { addLabel, canAdd, onAdd (inline JS for the add button) }
function openPartDetailsFor(part, opts) {
    opts = opts || {};
    const store = storesData[part.storeId] || null;
    const st = stockStatus(part);
    const catNames = { cpu: 'CPU / Processor', gpu: 'Graphics Card', motherboard: 'Motherboard', ram: 'Memory (RAM)', storage: 'Storage', psu: 'Power Supply', case: 'PC Case' };

    // Only show the facts this item actually has
    const facts = [];
    const add = (label, value) => { if (value !== null && value !== undefined && value !== '' && value !== 0) facts.push([label, value]); };
    add('Category', catNames[part.category] || part.category);
    add('Brand', part.brandName || part.brand);
    if (part.category === 'cpu' || part.category === 'motherboard') add('Socket', compatSocket(part) || part.socket);
    if (part.category === 'cpu') add('Cores', cpuCoreCount(part) || '');
    if (part.category === 'motherboard' || part.category === 'ram') add('Memory type', compatRam(part) || part.ram_type);
    if (part.category === 'motherboard') {
        add('Form factor', part.form_factor);
        add('RAM slots', part.ram_slots);
        add('M.2 slots', part.m2_slots);
        add('SATA ports', part.sata_ports);
    }
    if (part.category === 'ram') {
        add('Capacity', ramCapacityGB(part) ? ramCapacityGB(part) + 'GB' : '');
        add('Sticks', ramSticks(part));
    }
    if (part.category === 'storage') {
        add('Type', part.storage_type || (isM2Storage(part) ? 'NVMe / M.2' : isHardDisk(part) ? 'HDD' : ''));
        const gb = storageCapacityGB(part);
        add('Capacity', gb ? (gb >= 1000 ? (gb / 1000) + 'TB' : gb + 'GB') : '');
    }
    if (part.category === 'psu') add('Wattage', extractWattage(part) ? extractWattage(part) + 'W' : '');
    if (part.category === 'case') add('Fits', part.form_factor ? part.form_factor + ' and smaller' : '');

    let modal = document.getElementById('partDetailModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'partDetailModal';
        modal.className = 'pp-modal pp-modal-top';
        modal.addEventListener('click', e => { if (e.target === modal) closePartDetails(); });
        document.body.appendChild(modal);
        document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) closePartDetails(); });
    }
    modal.innerHTML = `
        <div class="pp-dialog part-detail" role="dialog" aria-modal="true" aria-label="${escapeHtml(part.name)}">
            <div class="pp-dialog-head">
                <h3>${escapeHtml(catNames[part.category] || 'Part details')}</h3>
                <button type="button" class="pp-icon" onclick="closePartDetails()" title="Close"><i class="fas fa-xmark"></i></button>
            </div>
            <div class="part-detail-body">
                <div class="part-detail-photo">
                    ${part.image
                        ? `<a href="${escapeHtml(part.image)}" target="_blank" rel="noopener" title="Open full-size photo"><img src="${escapeHtml(part.image)}" alt="${escapeHtml(part.name)}"></a>`
                        : `<div class="part-detail-nophoto"><i class="fas fa-microchip"></i><span>No photo</span></div>`}
                </div>
                <div class="part-detail-info">
                    <h2>${escapeHtml(ppDisplayName({ ...part, brandName: part.brandName || part.brand }))}</h2>
                    <div class="part-detail-price">₱${(part.price || 0).toLocaleString()}</div>
                    ${st.label ? `<div class="pp-avail ${st.cssClass}" style="margin-bottom:14px;">${st.dot} ${escapeHtml(st.label)}</div>` : ''}
                    ${facts.length ? `<dl class="part-facts">${facts.map(([k, v]) => `<dt>${escapeHtml(k)}</dt><dd>${escapeHtml(String(v))}</dd>`).join('')}</dl>` : ''}
                    ${part.specs ? `<h4>Full details</h4><p class="part-detail-specs">${escapeHtml(part.specs)}</p>` : ''}
                    <div class="part-detail-store">
                        <div><i class="fas fa-store"></i> <strong>${escapeHtml(part.storeName || '')}</strong></div>
                        ${store && store.address ? `<div><i class="fas fa-location-dot"></i> ${escapeHtml(store.address)}</div>` : ''}
                        ${store && store.contact ? `<div><i class="fas fa-phone"></i> ${escapeHtml(store.contact)}</div>` : ''}
                        ${store && storeMapUrl(store) ? `<div><a href="${storeMapUrl(store)}" target="_blank" rel="noopener"><i class="fas fa-map-location-dot"></i> View store on map</a></div>` : ''}
                    </div>
                    ${opts.blockedReason ? `<div class="part-detail-blocked"><i class="fas fa-ban"></i> <span><strong>Not compatible with your build.</strong> ${escapeHtml(opts.blockedReason)}</span></div>` : ''}
                    <div class="part-detail-actions">
                        ${opts.canAdd && opts.onAdd ? `<button type="button" class="btn-primary" onclick="${opts.onAdd}"><i class="fas ${opts.addIcon || 'fa-plus'}"></i> ${escapeHtml(opts.addLabel || 'Add')}</button>` : ''}
                        <button type="button" class="btn-secondary" onclick="closePartDetails()">Close</button>
                    </div>
                </div>
            </div>
        </div>`;
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closePartDetails() {
    const modal = document.getElementById('partDetailModal');
    if (modal) modal.classList.remove('open');
    // keep the page locked if the parts picker is still open underneath
    const picker = document.getElementById('ppModal');
    document.body.style.overflow = picker && picker.classList.contains('open') ? 'hidden' : '';
}

// Puts the part into its slot on the Custom Build page (All Stores view so
// parts from any store can be mixed).
function addPartToCustomBuild(idx) {
    const part = browsePartsShown[idx];
    if (!part) return;
    closePartDetails();
    selectedStore = '__all__';
    showPage('custom');
    if (typeof updateComponent === 'function') updateComponent(part.category, part.id);
    showToast(`Added ${part.name} to your Custom Build`);
}

function getPartSpecs(part) {
    // Store items describe themselves in free text; the structured fields
    // below (cores, vram, …) only exist on the built-in sample parts.
    if (part.source === 'store') return part.specs || '';
    if (part.socket) return `Socket: ${part.socket} | Cores: ${part.cores}`;
    if (part.power) return `Power: ${part.power}W | VRAM: ${part.vram}`;
    if (part.ramType) return `Socket: ${part.socket} | RAM: ${part.ramType}`;
    if (part.type) return `${part.type} | ${part.capacity}`;
    if (part.wattage) return `${part.wattage}W | ${part.rating}`;
    if (part.specs) return part.specs;
    return '';
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>"']/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        if (m === '"') return '&quot;';
        return '&#39;';
    });
}

// =====================
// Save Modal
// =====================
function openSaveModal() {
    const hasComponents = Object.values(customBuildComponents).some(c => c !== null);
    if (!hasComponents) {
        alert('Please select components first!');
        return;
    }
    if (!requireLoginToSave()) return;
    document.getElementById('saveModal').style.display = 'flex';
    document.getElementById('buildNameInput').value = 'My Custom Build';
}

function closeSaveModal() {
    document.getElementById('saveModal').style.display = 'none';
}

async function saveCurrentBuild() {
    const name = document.getElementById('buildNameInput').value;
    if (!name.trim()) {
        alert('Please enter a build name');
        return;
    }
    const totalPrice = Object.values(customBuildComponents).reduce((sum, c) => sum + (c?.price || 0), 0);
    const buildToSave = {
        name: name,
        components: { ...customBuildComponents },
        totalPrice: totalPrice,
        savedAt: new Date().toLocaleDateString(),
        isCustom: true,
        storeId: selectedStore,
        storeName: storesData[selectedStore]?.name
    };
    await persistBuild(buildToSave);
    savedBuilds.unshift(buildToSave);
    localStorage.setItem('compumatch_builds', JSON.stringify(savedBuilds));
    closeSaveModal();
    alert('Build saved successfully!');
    showPage('mybuilds');
}

// =====================
// Initialize
// =====================
// =====================
// Client Profile (logout + settings)
// =====================
function getClientEmail() { return localStorage.getItem('compumatch_user_email') || ''; }

// Building and browsing stay open to guests, but saving a build requires an
// account (so it can actually be tied to someone and shown back to them on
// any device). Call this right before a save action goes through.
function requireLoginToSave() {
    if (getClientEmail()) return true;
    const modal = document.getElementById('loginRequiredModal');
    if (modal) modal.style.display = 'flex';
    return false;
}

function closeLoginRequiredModal() {
    const modal = document.getElementById('loginRequiredModal');
    if (modal) modal.style.display = 'none';
}
function isClientLoggedIn() { return localStorage.getItem('compumatch_logged_in') === 'true'; }

// The "logged in" state above is just a localStorage flag — it can go stale
// (session expired server-side, cookies cleared, different browser/profile)
// while the UI still shows a signed-in profile menu. This checks the real
// PHP session and clears the stale local flags if they no longer match.
async function verifyLoginSession() {
    if (!isClientLoggedIn()) return; // nothing to verify for a guest
    try {
        const res = await fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'whoami' })
        });
        const data = await res.json();
        if (data.ok && data.logged_in === false) {
            localStorage.removeItem('compumatch_logged_in');
            localStorage.removeItem('compumatch_user_email');
            localStorage.removeItem('compumatch_user_name');
            renderProfileArea();
            if (currentPage === 'mybuilds') renderSavedBuilds();
        }
    } catch (e) {
        // Backend unreachable — keep trusting the local flag rather than
        // logging the person out just because the network hiccuped.
    }
}

function getClientName() {
    const name = localStorage.getItem('compumatch_user_name');
    if (name && name.trim()) return name.trim();
    const email = getClientEmail();
    return email ? email.split('@')[0] : 'Guest';
}

function getInitials(name) {
    const parts = (name || '').trim().split(/\s+/).filter(Boolean);
    if (!parts.length) return 'U';
    if (parts.length === 1) return parts[0].slice(0, 2).toUpperCase();
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
}

function renderProfileArea() {
    const area = document.getElementById('profileArea');
    if (!area) return;

    if (!isClientLoggedIn()) {
        area.innerHTML = `<a href="login.php" class="btn-primary">Sign In</a>`;
        return;
    }

    const name = getClientName();
    const email = getClientEmail();
    const initials = getInitials(name);

    area.innerHTML = `
        <button class="profile-trigger" onclick="toggleProfileDropdown(event)">
            <span class="avatar-circle">${initials}</span>
            <span class="profile-name">${name}</span>
            <i class="fas fa-chevron-down" style="font-size:0.7rem;"></i>
        </button>
        <div class="profile-dropdown" id="profileDropdown">
            <div class="profile-dropdown-header">
                <div class="avatar-circle lg">${initials}</div>
                <div>
                    <div class="pd-name">${name}</div>
                    <div class="pd-email">${email}</div>
                </div>
            </div>
            <button onclick="openProfileSettings()"><i class="fas fa-gear"></i> Settings</button>
            <button onclick="closeProfileDropdown(); showPage('mybuilds')"><i class="fas fa-folder"></i> My Builds</button>
            <button class="pd-logout" onclick="clientLogout()"><i class="fas fa-right-from-bracket"></i> Logout</button>
        </div>`;
}

function toggleProfileDropdown(e) {
    if (e) e.stopPropagation();
    const dd = document.getElementById('profileDropdown');
    if (dd) dd.classList.toggle('open');
}

function closeProfileDropdown() {
    const dd = document.getElementById('profileDropdown');
    if (dd) dd.classList.remove('open');
}

function clientLogout() {
    localStorage.removeItem('compumatch_logged_in');
    localStorage.removeItem('compumatch_user_email');
    localStorage.removeItem('compumatch_user_name');
    window.location.href = 'login.php';
}

function openProfileSettings() {
    closeProfileDropdown();
    document.getElementById('settingsName').value = localStorage.getItem('compumatch_user_name') || '';
    document.getElementById('settingsEmail').value = getClientEmail();
    document.getElementById('profileSettingsModal').style.display = 'flex';
}

function closeProfileSettings() {
    document.getElementById('profileSettingsModal').style.display = 'none';
}

async function saveProfileSettings() {
    const name = document.getElementById('settingsName').value.trim();
    const email = document.getElementById('settingsEmail').value.trim();
    const currentPassword = document.getElementById('settingsPassword').value;

    if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert('Please enter a valid email address.');
        return;
    }

    const oldEmail = getClientEmail();

    // Persist to the real database first so this works from any device.
    if (oldEmail) {
        if (!currentPassword) {
            alert('Please enter your current password to confirm these changes.');
            return;
        }
        try {
            const res = await fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'update_profile', email: oldEmail, password: currentPassword,
                    name, new_email: email || oldEmail
                })
            });
            const data = await res.json();
            if (!data.ok) { alert(data.error || 'Could not update profile.'); return; }
        } catch (e) {
            // Backend unreachable — keep working offline via localStorage only.
        }
    }

    if (name) localStorage.setItem('compumatch_user_name', name);
    else localStorage.removeItem('compumatch_user_name');

    if (email) {
        localStorage.setItem('compumatch_user_email', email);
        // Keep the registered-users map in sync so the name persists on next login
        const users = JSON.parse(localStorage.getItem('compumatch_users') || '{}');
        if (oldEmail && users[oldEmail] && oldEmail !== email) delete users[oldEmail];
        users[email] = { name: name || (users[email] && users[email].name) || '', email };
        localStorage.setItem('compumatch_users', JSON.stringify(users));
    }

    document.getElementById('settingsPassword').value = '';
    closeProfileSettings();
    renderProfileArea();
    showToast('Profile updated!');
}

// Best-effort: pull stores + inventory from the PHP/MySQL backend.
// If the backend isn't running, the app silently keeps using localStorage.
async function syncFromBackend() {
    // If the .php page already injected MySQL data server-side, use that and skip the fetch.
    if (typeof window !== 'undefined' && window.__BOOTSTRAP__) return;
    if (typeof CompuMatchAPI === 'undefined') return; // api.js not included
    try {
        const res = await CompuMatchAPI.getStores();
        const stores = res.stores || [];
        if (!stores.length) return;
        const fresh = {};
        for (const s of stores) {
            const inv = await CompuMatchAPI.getInventory(s.store_id);
            fresh[s.store_id] = {
                id: s.store_id,
                name: s.name,
                address: s.address || '',
                latitude: s.latitude != null ? Number(s.latitude) : null,
                longitude: s.longitude != null ? Number(s.longitude) : null,
                verified: s.permit_status ? s.permit_status === 'approved' : undefined,
                contact: s.contact || '',
                email: s.email || '',
                description: s.description || '',
                inventory: (inv.items || []).map(it => ({
                    id: it.id, category: it.category, name: it.name, brand: it.brand,
                    price: Number(it.price), stock: Number(it.stock),
                    lowStock: Number(it.lowStock != null ? it.lowStock : 5),
                    specs: it.specs || '', sold: Number(it.sold || 0),
                    socket: it.socket || null, ram_type: it.ram_type || null,
                    form_factor: it.form_factor || null, storage_type: it.storage_type || null,
                    sata_ports: it.sata_ports != null ? Number(it.sata_ports) : null,
                    m2_slots: it.m2_slots != null ? Number(it.m2_slots) : null,
                    ram_slots: it.ram_slots != null ? Number(it.ram_slots) : null
                }))
            };
        }
        storesData = fresh;
        localStorage.setItem('compumatch_stores', JSON.stringify(storesData));
        try { renderStoreSelection(); } catch (e) {}
        try { renderBrowseParts(); } catch (e) {}
        try { renderComponentSelectors(); } catch (e) {}
        console.log('[CompuMatch] Inventory loaded from MySQL database.');
    } catch (e) {
        console.warn('[CompuMatch] Backend not reachable — using local data.', e.message);
    }
}

function init() {
    initializeDemoStores();
    ensureDemoCases();
    ensurePcHubExtras();
    initBudgetControls();
    
    document.querySelectorAll('#useGroup .radio-card, #perfGroup .radio-card').forEach(card => {
        card.addEventListener('click', function () {
            this.parentElement.querySelectorAll('.radio-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
        });
    });
    
    document.querySelectorAll('#partCategoryFilter .radio-card').forEach(card => {
        card.addEventListener('click', function () {
            document.querySelectorAll('#partCategoryFilter .radio-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            renderBrowseParts();
        });
    });
    
    const browseBrandFilter = document.getElementById('browseBrandFilter');
    if (browseBrandFilter) {
        browseBrandFilter.addEventListener('change', () => renderBrowseParts());
    }

    const browseSearch = document.getElementById('browseSearch');
    if (browseSearch) {
        browseSearch.addEventListener('input', () => {
            const clearBtn = document.getElementById('browseSearchClear');
            if (clearBtn) clearBtn.style.display = browseSearch.value ? 'flex' : 'none';
            renderBrowseParts();
        });
    }

    renderProfileArea();

    // Close the profile dropdown when clicking outside of it
    document.addEventListener('click', function (e) {
        const menu = document.getElementById('profileArea');
        if (menu && !menu.contains(e.target)) closeProfileDropdown();
    });

    showPage('home');

    // Try to load live data from the PHP/MySQL backend (falls back to local)
    syncFromBackend();
    verifyLoginSession();
}

// Start the app
init();
// Highlight the starting page in the top menu once the page has loaded.
document.addEventListener('DOMContentLoaded', () => { try { highlightNav(currentPage); } catch (e) {} });

// Custom Build: the brand filter sits beside the store picker, and only
// shows once a store is chosen (same moment the parts list appears).
(function syncBrandFilterVisibility() {
    function run() {
        const content = document.getElementById('customBuildContent');
        const brand = document.getElementById('brandFilterSection');
        const row = brand && brand.closest('.cb-top-row');
        if (!content || !brand) return;
        const sync = () => {
            const on = content.style.display !== 'none';
            brand.style.display = on ? '' : 'none';
            if (row) row.classList.toggle('has-brands', on);
        };
        sync();
        new MutationObserver(sync).observe(content, { attributes: true, attributeFilter: ['style'] });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run); else run();
})();
