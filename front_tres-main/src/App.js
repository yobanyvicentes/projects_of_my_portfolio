import React from 'react';
import { BrowserRouter as Router, Switch, Route, Redirect } from "react-router-dom";

import { Header } from './components/ui/Header';

import {InventarioView} from './components/inventarios/InventarioView';
import { UsuarioView } from './components/usuarios/UsuarioView';
import { MarcaView } from './components/marcas/MarcaView';
import { TipoEquipoView } from './components/tipos/TipoEquipoView';
import { EstadoEquipoView } from './components/estados/EstadoEquipoView';

import {InventarioUpdate} from './components/inventarios/InventarioUpdate';
import { UsuarioUpdate } from './components/usuarios/UsuarioUpdate'
import { EstadoEquipoUpdate } from './components/estados/EstadoEquipoUpdate';
import { TipoEquipoUpdate } from './components/tipos/TipoEquipoUpdate';
import { MarcaUpdate } from './components/marcas/MarcaUpdate';

import { LoginButton } from './components/auth0/LoginButton';
import { useAuth0 } from "@auth0/auth0-react";


export const App = () => {
    const { isAuthenticated } = useAuth0();
    return <Router>
        {isAuthenticated ? (<>
            <Header />
            <Switch>
                <Route exact path='/' component={InventarioView} />

                <Route exact path='/inventarios' component={InventarioView} />
                <Route exact path='/usuarios' component={UsuarioView} />
                <Route exact path='/marcas' component={MarcaView} />
                <Route exact path='/estadoEquipos' component={EstadoEquipoView} />
                <Route exact path='/tipos' component={TipoEquipoView} />

                <Route exact path='/inventario/edit/:inventarioId' component={InventarioUpdate}/>
                <Route exact path='/usuario/edit/:usuarioId' component={UsuarioUpdate} />
                <Route exact path='/estadoEquipo/edit/:estadoEquipoId' component={EstadoEquipoUpdate} />
                <Route exact path='/tipoEquipo/edit/:tipoEquipoId' component={TipoEquipoUpdate} />
                <Route exact path='/marca/edit/:marcaId' component={MarcaUpdate} />

                <Redirect to='/' />
            </Switch>
        </>) : (<>
            <LoginButton />
        </>)}
    </Router>
}
