import React, { useState, useEffect } from 'react';
import { getProducts } from '../../services/product';
import { Link } from 'react-router-dom'
import Swal from 'sweetalert2';

export const ProductCard = () => {

  const [products, setProducts] = useState([]);

  const listarProducts = async () => {
    try {
      Swal.showLoading();
      const { data } = await getProducts();
      setProducts(data);
      Swal.close();
    } catch (error) {
      console.log('ocurrió un error')
      Swal.close();
    }
  };

  useEffect(() => {
    listarProducts();
  }, []);

  return (
    <div className="container">
        <div className='header-section'>
          <h5> Mis Productos </h5>
        </div>
        <div className="cards mt-2 mb-2 row row-cols-1 row-cols-md-4 g-4">
        {products.map((product) => {
            return (
                    <div className="col card-container" key={product._id}>
                        <div className="card product">
                            <img src={product.image} className="card-img-top" alt={product.image} />
                            <div className="card-body">
                                <h5 className="card-title truncate"> {`${product.name}`} </h5>
                                <hr />
                                <p className="card-text truncate">{`Precio: ${product.price}`}</p>
                                <p className="card-text truncate">{`Unidades: ${product.inventory}`}</p>
                                <p className="card-text truncate">{`Categoría: ${product.category.name}`}</p>
                                <p className="card-text truncate">
                                <Link to={`product/edit/${product._id}`}> Detalles... </Link>
                                </p>
                            </div>
                        </div>
                    </div>
                )
          })
        }
      </div>
    </div>
  )
}
