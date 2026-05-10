import { useState } from 'react';

export default function useToken() {
  const getToken = () => {
    try {
      const tokenString = localStorage.getItem('token');
      const userToken = JSON.parse(tokenString);
      return userToken
    } catch (error) {
      return "";
    }

  };

  const [token, setToken] = useState(getToken());

  const saveToken = (userToken) => {
    console.log("save token:", userToken)
    localStorage.setItem('token', JSON.stringify(userToken));
    setToken(userToken);
  };

  return { setToken: saveToken, token }
}
