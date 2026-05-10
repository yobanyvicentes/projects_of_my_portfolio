import { axiosInstance } from "../helpers/axios-config";

export const getProducts = () => {
    return axiosInstance.get('/product', {
        headers:{
            'Content-type' : 'application/json'
        }
    });
}
export const postProduct = (data) => {
    return axiosInstance.post('/product', data, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
export const getProductById = (productId) => {
    return axiosInstance.get(`/product/${productId}`, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
export const putProduct = (productId, data) => {
    return axiosInstance.put(`/product/edit/${productId}`,data,  {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
export const deleteProductById = (productId) => {
    return axiosInstance.delete(`product/delete/${productId}`, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
