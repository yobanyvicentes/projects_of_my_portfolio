import React, { useState, useEffect } from 'react'
import { CreateBrand } from './CreateBrand';
import { getBrands, deleteBrandById } from '../../services/brand';
import Swal from 'sweetalert2';
import { Link } from 'react-router-dom';

export const ViewBrand = () => {

  const [brands, setBrands] = useState([]);

  const listBrands = async () => {
    try {
      Swal.showLoading();
      const { data } = await getBrands();
      setBrands(data);
      Swal.close();
    } catch (error) {
      console.log('ocurrió un error')
    }
  };

  useEffect(() => {
    listBrands();
  }, []);

  const deleteBrand = async (brandId) => {
    try {
      Swal.showLoading();
      console.log(brandId);
      const { data } = await deleteBrandById(brandId);
      console.log(data);
      listBrands();
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
      <div className='card mt-3 mb-2'>
        <div className='card-header'>
          <h5>Marcas</h5>
        </div>
        <div className='card-body'>
          <CreateBrand listBrands={listBrands} />
          <div className='row mt-5'>
            <div className='col'>
              <table className="table">
                <thead>
                  <tr>
                    <th className="col-md-1">#</th>
                    <th className="col-md-3">Nombre</th>
                    <th className="col-md-1">ID</th>
                    <th className="col-md-3">Fecha Creación</th>
                    <th className="col-md-3">Fecha Actualización</th>
                    <th className="col-md-1">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  {
                    brands.map((brand) => {
                      return (
                        <tr key={brand._id}>
                          <th className="col-md-1" value='index'>{1 + brands.indexOf(brand)} </th>
                          <td className="col-md-3">{brand.name}</td>
                          <td className="col-md-1">{brand.internalId}</td>
                          <td className="col-md-3">{brand.createDate}</td>
                          <td className="col-md-3">{brand.updateDate}</td>
                          <td className="col-md-1">
                            <Link to={`brand/edit/${brand._id}`}>
                              <i class="fa-regular fa-pen-to-square"></i>
                            </Link>
                            <Link to={`brand`}>
                              <i class="fa-solid fa-trash" onClick={()=>{deleteBrand(brand._id)}}></i>
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
