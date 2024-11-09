-- Creación de modelo SQL para un minimarket (tablas)

CREATE TABLE MVCD_REGIONES(
    ID_REGION NUMBER,
    NOMBRE_REGION VARCHAR2(50),
    CONSTRAINT PK_MVCD_REGIONES PRIMARY KEY(ID_REGION)
);

CREATE TABLE MVCD_CALLE_DIRECCION(
    ID_CALLE NUMBER,
    CALLE_DIRECCION VARCHAR2(50),
    NRO_DIRECCION NUMBER,
    CONSTRAINT PK_MVCD_CALLE_DIRECCION PRIMARY KEY(ID_CALLE)
);

CREATE TABLE MVCD_CIUDADES(
    ID_CIUDAD_T NUMBER,
    NOMBRE_CIUDAD VARCHAR2(50),
    ID_CALLE NUMBER,
    ID_REGION NUMBER,
    CONSTRAINT PK_MVCD_CIUDADES PRIMARY KEY(ID_CIUDAD_T),
    CONSTRAINT FK_MVCD_CIUDADES_1 FOREIGN KEY(ID_CALLE) REFERENCES MVCD_CALLE_DIRECCION(ID_CALLE),
    CONSTRAINT FK_MVCD_CIUDADES_2 FOREIGN KEY(ID_REGION) REFERENCES MVCD_REGIONES(ID_REGION)
);

CREATE TABLE MVCD_ROLES(
    ID_ROL NUMBER,
    AREA_TRABAJO VARCHAR2(20),
    CONSTRAINT PK_MVCD_ROLES PRIMARY KEY(ID_ROL)
)

CREATE TABLE MVCD_TRABAJDORES(
    ID_TRABAJADOR NUMBER,
    TELEFONO_TRABAJADOR NUMBER,
    CORREO VARCHAR2(50),
    NOMBRE_TRABAJADOR VARCHAR2(50),
    APELLIDO1_TRABAJADOR VARCHAR2(50),
    APELLIDO2_TRABAJADOR VARCHAR2(50),
    ID_REGION NUMBER,
    ID_ROL NUMBER,
    CONSTRAINT PK_MVCD_TRABAJADORES PRIMARY KEY(ID_TRABAJADOR),
    CONSTRAINT FK_MVCD_TRABAJADORES_1 FOREIGN KEY(ID_REGION) REFERENCES MVCD_REGIONES(ID_REGION),
    CONSTRAINT FK_MVCD_TRABAJADORES_2 FOREIGN KEY(ID_ROL) REFERENCES MVCD_ROLES(ID_ROL)
);

CREATE TABLE MVCD_CUENTA_BANCARIA(
    ID_CUENTA_BANCARIA NUMBER,
    RUT NUMBER,
    TIPO_CUENTA_BANCARIA VARCHAR2(20),
    NRO_CUENTA_BANCARIA NUMBER,
    NOMBRE_BANCO VARCHAR2(20),
    ID_TRABAJADOR NUMBER,
    CONSTRAINT PK_MVCD_CUENTA_BANCARIA PRIMARY KEY(ID_CUENTA_BANCARIA),
    CONSTRAINT FK_MVCD_CUENTA_BANCARIA FOREIGN KEY(ID_TRABAJADOR) REFERENCES MVCD_TRABAJDORES(ID_TRABAJADOR)
);

CREATE TABLE MVCD_CLIENTES(
    ID_CLIENTE NUMBER,
    NOMBRE_CLIENTE VARCHAR2(50),
    APELLIDO1_CLIENTE VARCHAR2(50),
    APELLIDO2_CLIENTE VARCHAR2(50),
    TELEFONO_CLIENTE NUMBER,
    ID_REGION NUMBER,
    CONSTRAINT PK_MVCD_CLIENTES PRIMARY KEY(ID_CLIENTE),
    CONSTRAINT FK_MVCD_CLIENTES FOREIGN KEY(ID_REGION) REFERENCES MVCD_REGIONES(ID_REGION)
);

CREATE TABLE MVCD_PROMOCIONES(
    ID_PROMOCION NUMBER,
    DESCUENTO NUMBER,
    CONSTRAINT PK_MVCD_PROMOCIONES PRIMARY KEY(ID_PROMOCION)
);

CREATE TABLE MVCD_PRODUCTOS(
    ID_PRODUCTO NUMBER,
    NOMBRE_PRODUCTO VARCHAR2(20),
    TIPO_PRODUCTO VARCHAR2(20),
    PRECIO_PRODUCTO NUMBER,
    CONSTRAINT PK_MVCD_PRODUCTOS PRIMARY KEY(ID_PRODUCTO)
);

CREATE TABLE MVCD_INVENTARIO(
    ID_INVENTARIO NUMBER,
    CANTIDAD_INVENTARIO NUMBER,
    ID_PRODUCTO NUMBER,
    CONSTRAINT PK_MVCD_INVENTARIO PRIMARY KEY(ID_INVENTARIO),
    CONSTRAINT FK_MVCD_INVENTARIO FOREIGN KEY(ID_PRODUCTO) REFERENCES MVCD_PRODUCTOS(ID_PRODUCTO)
);

CREATE TABLE MVCD_INSUMOS(
    ID_INSUMO NUMBER,
    NOMBRE_INSUMO VARCHAR2(50),
    PRECIO_INSUMO NUMBER,
    CONSTRAINT PK_MVCD_INSUMOS PRIMARY KEY(ID_INSUMO)
);

CREATE TABLE MVCD_PROVEEDORES(
    ID_PROVEEDOR NUMBER,
    NOMBRE_PROVEEDOR VARCHAR2(50),
    TELEFONO_PROVEEDOR NUMBER,
    TIPO_PROVEEDOR VARCHAR2(20),
    ID_REGION NUMBER,
    CONSTRAINT PK_MVCD_PROVEEDORES PRIMARY KEY(ID_PROVEEDOR),
    CONSTRAINT FK_MVCD_PROVEEDORES FOREIGN KEY(ID_REGION) REFERENCES MVCD_REGIONES(ID_REGION)
);

CREATE TABLE MVCD_PAGO_PROVEEDOR(
    ID_PAGO_PROVEEDOR NUMBER,
    FECHA_PAGO_PROVEEDOR DATE,
    MONTO_PAGO_PROVEEDOR NUMBER,
    ID_PROVEEDOR NUMBER,
    CONSTRAINT PK_MVCD_PAGO_PROVEEDOR PRIMARY KEY(ID_PAGO_PROVEEDOR),
    CONSTRAINT FK_MVCD_PAGO_PROVEEDOR FOREIGN KEY(ID_PROVEEDOR) REFERENCES MVCD_PROVEEDORES(ID_PROVEEDOR)
);

CREATE TABLE MVCD_PAGO_CLIENTE(
    ID_PAGO_CLIENTE NUMBER,
    FECHA_PAGO_CLIENTE DATE,
    MONTO_PAGO_CLIENTE NUMBER,
    ID_TRABAJADOR NUMBER,
    ID_CLIENTE NUMBER,
    CONSTRAINT PK_MVCD_PAGO_CLIENTE PRIMARY KEY(ID_PAGO_CLIENTE),
    CONSTRAINT FK_MVCD_PAGO_CLIENTE_1 FOREIGN KEY(ID_CLIENTE) REFERENCES MVCD_CLIENTES(ID_CLIENTE),
    CONSTRAINT FK_MVCD_PAGO_CLIENTE_2 FOREIGN KEY(ID_TRABAJADOR) REFERENCES MVCD_TRABAJADORES(ID_TRABAJADOR),
);

CREATE TABLE MVCD_CABECERA_VENTA(
    ID_CABECERA_VENTA NUMBER,
    FECHA_VENTA DATE,
    TOTAL_VENTA NUMBER,
    ID_TRABAJADOR NUMBER,
    ID_CLIENTE NUMBER,
    CONSTRAINT PK_MVCD_CABECERA_VENTA PRIMARY KEY(ID_CABECERA_VENTA),
    CONSTRAINT FK_MVCD_CABECERA_VENTA_1 FOREIGN KEY(ID_TRABAJADOR) REFERENCES MVCD_TRABAJADORES(ID_TRABAJADOR),
    CONSTRAINT FK_MVCD_CABECERA_VENTA_2 FOREIGN KEY(ID_CLIENTE) REFERENCES MVCD_CLIENTES(ID_CLIENTE)
);

CREATE TABLE MVCD_CUERPO_VENTA(
    ID_CUERPO_VENTA NUMBER,
    CANTIDAD_VENTA NUMBER,
    SUBTOTAL_VENTA NUMBER,
    ID_CABECERA_VENTA NUMBER,
    ID_PRODUCTO NUMBER,
    CONSTRAINT PK_MVCD_CUERPO_VENTA PRIMARY KEY(ID_CUERPO_VENTA),
    CONSTRAINT FK_MVCD_CUERPO_VENTA_1 FOREIGN KEY(ID_TRABAJADOR) REFERENCES MVCD_TRABAJADORES(ID_TRABAJADOR),
    CONSTRAINT FK_MVCD_CUERPO_VENTA_2 FOREIGN KEY(ID_CLIENTE) REFERENCES MVCD_CLIENTES(ID_CLIENTE)
);

CREATE TABLE MVCD_DETALLE_PROVEEDOR_PRODUCTO(
    ID_PROVEEDOR NUMBER,
    ID_PRODUCTO NUMBER,
    CONSTRAINT PK_MVCD_DETALLE_PROVEEDOR_PRODUCTO PRIMARY KEY(ID_PROVEEDOR, ID_PRODUCTO),
    CONSTRAINT FK_MVCD_DETALLE_PROVEEDOR_PRODUCTO_1 FOREIGN KEY(ID_PROVEEDOR) REFERENCES MVCD_PROVEEDORES(ID_PROVEEDOR),
    CONSTRAINT FK_MVCD_DETALLE_PROVEEDOR_PRODUCTO_2 FOREIGN KEY(ID_PRODUCTO) REFERENCES MVCD_PRODUCTOS(ID_PRODUCTO)
);

CREATE TABLE MVCD_DETALLE_PROVEEDOR_INSUMO(
    ID_PROVEEDOR NUMBER,
    ID_INSUMO NUMBER,
    CONSTRAINT PK_MVCD_DETALLE_PROVEEDOR_INSUMO PRIMARY KEY(ID_PROVEEDOR, ID_INSUMO),
    CONSTRAINT FK_MVCD_DETALLE_PROVEEDOR_INSUMO_1 FOREIGN KEY(ID_PROVEEDOR) REFERENCES MVCD_PROVEEDORES(ID_PROVEEDOR),
    CONSTRAINT FK_MVCD_DETALLE_PROVEEDOR_INSUMO_2 FOREIGN KEY(ID_INSUMO) REFERENCES MVCD_INSUMOS(ID_INSUMO)
);
