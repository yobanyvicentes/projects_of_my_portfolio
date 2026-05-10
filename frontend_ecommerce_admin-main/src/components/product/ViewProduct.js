import React, { useState, useEffect } from 'react'
import { CreateProduct } from './CreateProduct';
import { getProducts, deleteProductById } from '../../services/product';
import Swal from 'sweetalert2';
import { Link } from 'react-router-dom';

export const ViewProduct = () => {

  const [products, setProducts] = useState([]);

  const listProducts = async () => {
    try {
      Swal.showLoading();
      const { data } = await getProducts();
      console.log("data", data)
      setProducts(data);
      Swal.close();
    } catch (error) {
      console.log('ocurrió un error')
    }
  };

  useEffect(() => {
    listProducts();
    console.log(listProducts())
  }, []);

  const deleteProduct = async (productId) => {
    try {
      Swal.showLoading();
      console.log(productId);
      const { data } = await deleteProductById(productId);
      console.log(data);
      listProducts();
      Swal.close();
    } catch (error) {
      Swal.fire({
        allowOutsideClick: false, title: 'Error', text: 'Hubo un error al intentar eliminar'
      });
      console.log('ocurrió un error');
    }
  }

  return (
    <div className='container-fluid'>
      <div className='card mt-1 mb-1'>
        <div className='card-header'>
          <h5>Productos</h5>
        </div>
        <div className='card-body'>
          <CreateProduct listProducts={listProducts} />
          <div className='row mt-2'>
            <div className='col'>
              <table className="table">
                <thead>
                  <tr>
                    <th className="col-md-1">#</th>
                    <th className="col-md-2">Nombre</th>
                    <th className="col-md-2">Descripción</th>
                    <th className="col-md-2">Categoria</th>
                    <th className="col-md-1">Marca</th>
                    <th className="col-md-2">Precio</th>
                    <th className="col-md-1">Inventario</th>
                    <th className="col-md-1">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  {
                    products.map((product) => {
                      return (
                        <tr key={product._id}>
                          <th className="col-md-1" value='index'>{1 + products.indexOf(product)} </th>
                          <td className="col-md-2">{product.name}</td>
                          <td className="col-md-2">{product.description}</td>
                          <td className="col-md-2">{product.category.name}</td>
                          <td className="col-md-1">{product.brand.name}</td>
                          <td className="col-md-2">{product.price}</td>
                          <td className="col-md-1">{product.inventory}</td>
                          <td className="col-md-1">
                            <Link to={`product/edit/${product._id}`}>
                              <i class="fa-regular fa-pen-to-square"></i>
                            </Link>
                            <Link to={`product`}>
                              <i class="fa-solid fa-trash" onClick={()=>{deleteProduct(product._id)}}></i>
                            </Link>
                          </td>
                        </tr>
                      )
                    })
                  }
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
