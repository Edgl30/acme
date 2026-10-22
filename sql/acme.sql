
CREATE DATABASE IF NOT EXISTS acme
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE acme;

CREATE TABLE conductores (
    cedula VARCHAR(20) PRIMARY KEY,
    primer_nombre VARCHAR(60) NOT NULL,
    segundo_nombre VARCHAR(60) NULL,
    apellidos VARCHAR(120) NOT NULL,
    direccion VARCHAR(180) NOT NULL,
    telefono VARCHAR(25) NOT NULL,
    ciudad VARCHAR(80) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE propietarios (
    cedula VARCHAR(20) PRIMARY KEY,
    primer_nombre VARCHAR(60) NOT NULL,
    segundo_nombre VARCHAR(60) NULL,
    apellidos VARCHAR(120) NOT NULL,
    direccion VARCHAR(180) NOT NULL,
    telefono VARCHAR(25) NOT NULL,
    ciudad VARCHAR(80) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE vehiculos (
    placa VARCHAR(10) PRIMARY KEY,
    color VARCHAR(40) NOT NULL,
    marca VARCHAR(60) NOT NULL,
    tipo ENUM('particular', 'publico') NOT NULL,
    conductor_cedula VARCHAR(20) NOT NULL,
    propietario_cedula VARCHAR(20) NOT NULL,

    INDEX idx_vehiculo_conductor (conductor_cedula),
    INDEX idx_vehiculo_propietario (propietario_cedula),

    CONSTRAINT fk_vehiculo_conductor
        FOREIGN KEY (conductor_cedula)
        REFERENCES conductores(cedula),

    CONSTRAINT fk_vehiculo_propietario
        FOREIGN KEY (propietario_cedula)
        REFERENCES propietarios(cedula)
) ENGINE=InnoDB;
