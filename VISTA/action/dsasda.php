<?php
 function cambiarEstado($idCompra, $accion) {
    $response = ["success" => false, "msg" => "Acción no válida."];
    $objAbmUsuario = new ABMUsuario();
    $objAbmCompra = new ABMCompra();
    $objAbmProducto = new ABMProducto();
    $objAbmCompraItem = new ABMCompraItem();

    // Buscar estado actual de la compra
    $estadoActual = $this->buscar(['idcompra' => $idCompra]);
    if (empty($estadoActual)) {
      $response["msg"] = "No hay estado.";
      return $response;
    }

    $ultimoEstado = end($estadoActual);
    $items = $objAbmCompraItem->buscar(['idcompra' => $idCompra]);
    $productos = [];

    // Validar stock de productos
    $cantidadValida = true;
    foreach ($items as $item) {
      $producto = $objAbmProducto->buscar(["idproducto" => $item->getidproducto()])[0];
      if ($producto->getprocantstock() < $item->getcicantidad()) {
        $cantidadValida = false;
      }
      $productos[] = [
        'idproducto' => $producto->getidproducto(),
        'precio' => $producto->getprecio(),
        'pronombre' => $producto->getpronombre(),
        'prodetalle' => $producto->getprodetalle(),
        'procantstock' => $producto->getprocantstock(),
        'cantidad' => $item->getcicantidad(),
      ];
    }

    $compra = $objAbmCompra->buscar(['idcompra' => $idCompra])[0];
    $usuario = $objAbmUsuario->buscar(["idusuario" => $compra->getidusuario()])[0];
    $nombre = $usuario->getusnombre();
    $email = $usuario->getusmail();

    switch ($accion) {
      case 'aceptar':
        if ($cantidadValida && $this->actualizarEstado($ultimoEstado, $idCompra, 2)) {
          foreach ($productos as $prod) {
            $paramProd = [
              'idproducto' => $prod['idproducto'],
              'precio' => $prod['precio'],
              'pronombre' => $prod['pronombre'],
              'prodetalle' => $prod['prodetalle'],
              'procantstock' => $prod['procantstock'] - $prod["cantidad"],
            ];
            $objAbmProducto->modificacion($paramProd);
          }
          $response = ["success" => true, "msg" => "Estado actualizado a aceptado."];
          enviarCorreo($email, $nombre, 'Compra Aceptada', 'Tu compra ha sido aceptada.');
        } else {
          $response["msg"] = "No hay suficiente stock.";
        }
        break;

      case 'enviar':
        if ($this->actualizarEstado($ultimoEstado, $idCompra, 3)) {
          $response = ["success" => true, "msg" => "Estado actualizado a enviado."];
          enviarCorreo($email, $nombre, 'Compra Enviada', 'Tu compra ha sido enviada.');
        } else {
          $response["msg"] = "No se pudo actualizar el estado.";
        }
        break;

      case 'cancelar':
        if ($this->actualizarEstado($ultimoEstado, $idCompra, 4)) {
          foreach ($productos as $prod) {
            $paramProd = [
              'idproducto' => $prod['idproducto'],
              'precio' => $prod['precio'],
              'pronombre' => $prod['pronombre'],
              'prodetalle' => $prod['prodetalle'],
              'procantstock' => $prod['procantstock'] + $prod["cantidad"],
            ];
            $objAbmProducto->modificacion($paramProd);
          }
          $response = ["success" => true, "msg" => "Estado actualizado a cancelado."];
          enviarCorreo($email, $nombre, 'Compra Cancelada', 'Tu compra ha sido cancelada.');
        } else {
          $response["msg"] = "No se pudo actualizar el estado.";
        }
        break;

      default:
        $response["msg"] = "Acción no reconocida.";
        break;
    }

    return $response;
  }