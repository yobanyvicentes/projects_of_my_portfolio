import { axiosInstance } from "../helpers/axios-config";


export const getBrands = () => {
    return axiosInstance.get('/brand', {
        headers:{
            'Content-type' : 'application/json',
        }
    });
}
export const postBrand = (data) => {
    return axiosInstance.post('/brand', data, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
export const getBrandById = (brandId) => {
    return axiosInstance.get(`/brand/${brandId}`, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
export const putBrand = (brandId, data) => {
    return axiosInstance.put(`brand/edit/${brandId}`,data,  {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
export const deleteBrandById = (brandId) => {
    return axiosInstance.delete(`brand/delete/${brandId}`, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
