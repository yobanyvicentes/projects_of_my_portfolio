import React, { useState, useEffect } from "react";
import { postProduct } from "../../services/product";
import { getBrands } from "../../services/brand";
import { getCategories } from "../../services/category";
import Swal from "sweetalert2";

export const CreateProduct = ({ listProducts }) => {
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

  const [formValues, setFormValues] = useState({});
  const {
    name = "",
    internal_id = "",
    description = "",
    image = "",
    price = "",
    inventory = "",
    brand = "",
    category = "",
  } = formValues;

    const handleOnSubmit = async (e) => {
        e.preventDefault();
        const productModel = { name, internal_id, description, image, price: parseInt(price), inventory: parseInt(inventory), brand: {_id: brand}, category: {_id: category}};
        console.log(1, productModel);
        try {
        Swal.showLoading();
        const { data } = await postProduct(productModel);
        console.log(data);
        listProducts();
        setFormValues("");
        Swal.close();
        } catch (error) {
        Swal.fire("Error", "hubo un error al crear el producto", "error");
        console.log("error al crear el producto");
        }
    };

    const handleOnChange = ({ target }) => {
        const { name, value } = target;
        setFormValues({ ...formValues, [name]: value });
    };

  return (
    <div className="row">
      <div className="col">
        <form
          className="form"
          onSubmit={(e) => {
            handleOnSubmit(e);
          }}
        >
          <div className="row" te>
            <h5>Crear Producto</h5>
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
            <div className="col-md-2">
              <div className="mb-1 mt-2">
                <button className="btn btn-primary mt-4" type="onSubmit">
                  Guardar
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  );
};
