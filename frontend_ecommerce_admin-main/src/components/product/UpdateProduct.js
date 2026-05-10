import React, { useState, useEffect } from "react";
import Swal from "sweetalert2";
import { getBrands } from "../../services/brand";
import { getCategories } from "../../services/category";
import { useParams, useHistory } from "react-router-dom";
import { getProductById, putProduct } from "../../services/product";

export const UpdateProduct = () => {
const history = useHistory();

const [brands, setBrands] = useState([]);
const [categories, setCategories] = useState([]);

const listBrands = async () => {
    try {
    const { data } = await getBrands();
    setBrands(data);
    } catch (error) {
    console.log(error);
    }
};
useEffect(() => {
    listBrands();
}, []);

const listCategories = async () => {
    try {
    const { data } = await getCategories();
    setCategories(data);
    } catch (error) {
    console.log(error);
    }
};
useEffect(() => {
    listCategories();
}, []);

const { productId = "" } = useParams();
const [product, setProduct] = useState({});

const [formValues, setFormValues] = useState({});
const {
    name = "",
    internal_id = "",
    description = "",
    image = "",
    price = "",
    inventory = "",
    brand,
    category,
} = formValues;

const getProduct = async () => {
    try {
    Swal.showLoading();
    Swal.close();
    const { data } = await getProductById(productId);
    let { brand, category} = data;
    let brandId = brand._id;
    let categoryId = category._id;
    data.brand = brandId;
    data.category = categoryId;
    console.log(4, data);
    setProduct(data);
    } catch (error) {
    Swal.close();
    console.log(error);
    }
};

useEffect(() => {
    getProduct();
}, [productId]);

useEffect(() => {
    setFormValues({
    name: product.name,
    internal_id: product.internalId,
    description: product.description,
    image: product.image,
    price: product.price,
    inventory: product.inventory,
    brand: product.brand,
    category: product.category,
    });
}, [product]);

const handleOnSubmit = async (e) => {
    e.preventDefault();
    const productModel = {
    name,
    internal_id,
    description,
    image,
    price: parseInt(price),
    inventory: parseInt(inventory),
    brand: { _id: brand },
    category: { _id: category },
    };
    try {
    Swal.fire({
        allowOutsideClick: false,
        title: "Cargando....",
        text: "Por favor espere",
        timer: 2000, //milisegundos
    });
    const { data } = await putProduct(productId, productModel);
    console.log(data);
    Swal.close();
    setFormValues("");
    history.push("/product");
    } catch (error) {
    Swal.fire("Error", "hubo un error al intentar actualizar", "error");
    console.log("error al actualizar la producto");
    }
};

const handleOnChange = ({ target }) => {
    const { name, value } = target;
    setFormValues({ ...formValues, [name]: value });
};

return (
    <div className="container-fluid">
    <div className="card mt-3 mb-2">
        <div className="card-header">
        <h5>Productos</h5>
        </div>
        <div className="card-body">
        <div className="row">
            <div className="col">
            <form
                className="form"
                onSubmit={(e) => {
                handleOnSubmit(e);
                }}
            >
                <div className="row" te>
                <h5>Actualizar Producto</h5>
                </div>
                <div className="row">
                <div className="col-md-3">
                    <div className="mb-1">
                    <label className="form-label" for="nameId">
                        Nombre
                    </label>
                    <input
                        className="form-control"
                        type="text"
                        name="name"
                        value={name}
                        id="nameId"
                        required
                        onChange={(e) => {
                        handleOnChange(e);
                        }}
                    />
                    </div>
                </div>
                <div className="col-md-3">
                    <div className="mb-1">
                    <label className="form-label" for="internalId">
                        ID interno
                    </label>
                    <input
                        className="form-control"
                        name="internal_id"
                        value={internal_id}
                        id="internalId"
                        required
                        onChange={(e) => {
                        handleOnChange(e);
                        }}
                    />
                    </div>
                </div>
                <div className="col-md-3">
                    <div className="mb-1">
                    <label className="form-label" for="descriptionId">
                        Descripción
                    </label>
                    <input
                        className="form-control"
                        name="description"
                        value={description}
                        id="descriptionId"
                        required
                        onChange={(e) => {
                        handleOnChange(e);
                        }}
                    />
                    </div>
                </div>
                <div className="col-md-3">
                    <div className="mb-1">
                    <label className="form-label" for="imageId">
                        Imagen
                    </label>
                    <input
                        className="form-control"
                        name="image"
                        value={image}
                        id="imageId"
                        required
                        onChange={(e) => {
                        handleOnChange(e);
                        }}
                    />
                    </div>
                </div>
                <div className="col-md-3">
                    <div className="mb-1">
                    <label className="form-label" for="priceId">
                        Precio
                    </label>
                    <input
                        className="form-control"
                        name="price"
                        value={price}
                        id="priceId"
                        required
                        onChange={(e) => {
                        handleOnChange(e);
                        }}
                    />
                    </div>
                </div>
                <div className="col-md-2">
                    <div className="mb-1">
                    <label className="form-label" for="inventoryId">
                        Inventario
                    </label>
                    <input
                        className="form-control"
                        name="inventory"
                        value={inventory}
                        id="inventoryId"
                        required
                        onChange={(e) => {
                        handleOnChange(e);
                        }}
                    />
                    </div>
                </div>
                <div className="col-md-2">
                    <div className="mb-1">
                    <label className="form-label" for="brandId">
                        Marca
                    </label>
                    <select
                        className="form-control"
                        name="brand"
                        value={brand}
                        id="brandId"
                        required
                        onChange={(e) => {
                        handleOnChange(e);
                        }}
                    >
                        <option selected> -seleccionar-</option>
                        {brands.map((brand) => {
                        return (
                            <option key={brand._id} value={brand._id}>
                            {brand.name}
                            </option>
                        );
                        })}
                    </select>
                    </div>
                </div>
                <div className="col-md-3">
                    <div className="mb-1">
                    <label className="form-label" for="categoryId">
                        Categoria
                    </label>
                    <select
                        className="form-control"
                        name="category"
                        value={category}
                        id="categoryId"
                        required
                        onChange={(e) => {
                        handleOnChange(e);
                        }}
                    >
                        <option selected> -seleccionar-</option>
                        {categories.map((category) => {
                        return (
                            <option key={category._id} value={category._id}>
                            {category.name}
                            </option>
                        );
                        })}
                    </select>
                    </div>
                </div>
                <div className="col-md-4">
                    <div className="mb-3 mt-2">
                    <button className="btn btn-primary mt-4" type="onSubmit">
                        Guardar
                    </button>
                    </div>
                </div>
                </div>
            </form>
            </div>
        </div>
        </div>
    </div>
    </div>
);
};
