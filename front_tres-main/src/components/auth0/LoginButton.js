import React from "react";
import { useAuth0 } from "@auth0/auth0-react";

export const LoginButton = () => {
  const { loginWithRedirect } = useAuth0();
  return (
    <div className="">
      <div className="row">
        <div className="col">
          <p></p>
        </div>
      </div>
      <div>
        <div>
          <p></p>
        </div>
      </div>
      <div className="row" >
        <div className="col" style={{  height: "100%"}}>
          <button className="btn btn-primary text-start" style={{ backgroundImage: `url(https://ingernet.com.co/wp-content/uploads/2016/04/suministros-y-equipos.jpg)`, height: "400px", width: "100%" }} onClick={() => loginWithRedirect()}><h1>INGRESAR AL SISTEMA</h1></button>
        </div>
      </div>
    </div>
  );
};
