import React, { useState, useEffect } from 'react'
import { CreateCategory } from './CreateCategory';
import { getCategories } from '../../services/category';
import Swal from 'sweetalert2';
import { Link } from 'react-router-dom';

export const ViewCategory = () => {

  const [categories, setCategories] = useState([]);

  const listCategories = async () => {
    try {
      Swal.showLoading();
      const { data } = await getCategories();
      setCategories(data);
      Swal.close();
    } catch (error) {
      console.log('ocurrió un error')
      Swal.close();
    }
  };

  useEffect(() => {
    listCategories();
  }, []);

  return (
    <div className='container-fluid'>
      <div className='card mt-3 mb-2'>
        <div className='card-header'>
          <h5>Categorías</h5>
        </div>
        <div className='card-body'>
          <CreateCategory listCategories={listCategories} />
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
                    categories.map((category) => {
                      return (
                        <tr key={category._id}>
                          <th className="col-md-1" value='index'>{1 + categories.indexOf(category)} </th>
                          <td className="col-md-3">{category.name}</td>
                          <td className="col-md-1">{category.internalId}</td>
                          <td className="col-md-3">{category.createDate}</td>
                          <td className="col-md-3">{category.updateDate}</td>
                          <td className="col-md-1">
                            <Link to={`category/edit/${category._id}`}>
                              <i class="fa-regular fa-pen-to-square"></i>
                            </Link>
                            <Link to={`category`}>
                              <i class="fa-solid fa-trash"></i>
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
