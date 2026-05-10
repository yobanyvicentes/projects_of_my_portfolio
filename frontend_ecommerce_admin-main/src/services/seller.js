import { axiosInstance } from "../helpers/axios-config";

export const getSellers = () => {
    return axiosInstance.get('seller', {
        headers:{
            'Content-type' : 'application/json'
        }
    });
}
export const postSeller = (data) => {
    return axiosInstance.post('seller', data, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
export const getSellerById = (sellerId) => {
    return axiosInstance.get(`seller/${sellerId}`, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
export const putSeller = (sellerId, data) => {
    return axiosInstance.put(`seller/${sellerId}`,data,  {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
export const deleteSellerById = (sellerId) => {
    return axiosInstance.delete(`seller/delete/${sellerId}`, {
        headers:{
            'Content-type' : 'application/json'
        }
    })
}
