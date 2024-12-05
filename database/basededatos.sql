CREATE OR REPLACE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NULL,
    apellidos VARCHAR(100) NULL,
    usuario VARCHAR(50) NULL,
    contrasenia VARCHAR(500) NULL,
    celular INT NULL,
    celulartrabajo INT NULL,
    carnet VARCHAR(50) NULL,
    foto VARCHAR(500) NULL,
    tipo VARCHAR(500) NULL,
    created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE OR REPLACE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NULL,
    apellidos VARCHAR(100) NULL,
    usuario VARCHAR(50) NULL,
    contrasenia VARCHAR(500) NULL,
    celular INT NULL,
    edad INT NULL,
    fechnac DATE NULL,
    carnet VARCHAR(50) NULL,
    foto VARCHAR(500) NULL,
    created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE OR REPLACE TABLE infoclientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    diagnostico VARCHAR(100) NULL,
    tipotratamiento VARCHAR(100) NULL,
    duracion INT NULL,
    fechaadmision DATE NULL,
    tutor VARCHAR(80) NULL,
    frecuencia VARCHAR(100) NULL,
    objgenerales VARCHAR(300) NULL,
    fisico VARCHAR(300) NULL,
    emocional VARCHAR(300) NULL,
    cognitivo VARCHAR(300) NULL,
    social VARCHAR(300) NULL,
    metodosausar VARCHAR(300) NULL,
    notas TEXT NULL,
    created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE OR REPLACE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_infocliente INT NOT NULL,
    precio VARCHAR(100) NULL,
    saldo VARCHAR(100) NULL,
    pagado INT NULL,
    horario DATE NULL,
    tipo VARCHAR(80) NULL,
    descuento VARCHAR(100) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_infocliente) REFERENCES infoclientes(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE OR REPLACE TABLE archivospagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pago INT NOT NULL,
    monto VARCHAR(100) NULL,
    fechapago DATE NULL,
    horapago TIME NULL,
    file VARCHAR(300) NULL,
    observacion TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pago) REFERENCES pagos(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE OR REPLACE TABLE ciclos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pago INT NOT NULL,
    nrociclo INT NULL,
    sesion VARCHAR(30) NULL,
    estadosesion VARCHAR(30) NULL,
    fecha DATE NULL,
    estadopago VARCHAR(30) NULL,
    eri VARCHAR(60) NULL,
    cim VARCHAR(60) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pago) REFERENCES pagos(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE OR REPLACE TABLE plandeintervencions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_infocliente INT NOT NULL,
    momento VARCHAR(40) NULL,
    objetivo VARCHAR(40) NULL,
    foco VARCHAR(40) NULL,
    mlt VARCHAR(40) NULL,
    enfoque VARCHAR(40) NULL,
    duracion VARCHAR(40) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_infocliente) REFERENCES infoclientes(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE OR REPLACE TABLE subplandeintervencions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_plandeintervencion INT NOT NULL,
    categoria VARCHAR(80) NULL,
    nombre VARCHAR(80) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_plandeintervencion) REFERENCES plandeintervencions(id) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE OR REPLACE TABLE plandeintervencionsciclos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_plandeintervencion INT NOT NULL,
    id_ciclo INT NOT NULL,
    ejecucion TEXT NULL,
    apuntes TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_plandeintervencion) REFERENCES plandeintervencions(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_ciclo) REFERENCES ciclos(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE OR REPLACE TABLE matrizescalas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria VARCHAR(60) NULL,
    nombrematriz VARCHAR(60) NULL,
    valor VARCHAR(15) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE OR REPLACE TABLE submatrizescalas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_matrizescala INT NOT NULL,
    tipo VARCHAR(100) NULL,
    nombresubmatriz VARCHAR(100) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_matrizescala) REFERENCES matrizescalas(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE OR REPLACE TABLE inicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    archivo VARCHAR(200) NULL,
    titulo VARCHAR(100) NULL,
    subtitulo VARCHAR(100) NULL,
    descripcion TEXT NULL,
    categoria VARCHAR(50) NULL,
    link VARCHAR(150) NULL,
    costo VARCHAR(20) NULL,
    cupos INT NULL,
    fecha DATE NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `personal_access_tokens` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `tokenable_type` VARCHAR(255) NOT NULL,
    `tokenable_id` BIGINT(20) UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `token` VARCHAR(64) NOT NULL,
    `abilities` TEXT,
    `last_used_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`, `tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;