<?php
/**
 * Created by PhpStorm.
 * User: Jsv_S
 * Date: 12/07/2016
 * Time: 16:12
 */

namespace Creativados\Inmovilla;


class Properties extends PropertyCallIterator {
	const WHERE_FIELDS = [
		"cod_ofer"     => 1,
		"ref"          => "",
		"keyacci"      => [ 1, 2, 3, 4, 5, 6, 7, 8, 9 ],
		"precioinmo"   => 1,
		"outlet"       => 1,
		"precioalq"    => 1,
		"tipomensual"  => [ "MES", "QUI", "SEM", "DIA", "FIN" ],
		"numfotos"     => 1,
		"nbtipo"       => "",
		"ciudad"       => "",
		"zona"         => "",
		"numagencia"   => 1,
		"m_parcela"    => 1,
		"m_uties"      => 1,
		"m_cons"       => 1,
		"m_terraza"    => 1,
		"banyos"       => 1,
		"aseos"        => 1,
		"habdobles"    => 1,
		"habitaciones" => 1,
		"total_hab"    => 1,
		"distmar"      => 1,
		"ascensor"     => [ 0, 1 ],
		"aire_con"     => [ 0, 1 ],
		"piscina_com"  => [ 0, 1 ],
		"piscina_prop" => [ 0, 1 ],
		"diafano"      => [ 0, 1 ],
		"todoext"      => [ 0, 1 ],
		"foto"         => "",
		"calefacción"  => [ 0, 1 ],
		"aire_con"     => [ 0, 1 ],
		"trastero"     => [ 0, 1 ],
		"key_tipo"     => 1,
		"key_loca"     => 1,
		"key_zona"     => 1,
		"conservacion" => 1
	];
	const WHERE_CHANGES = [
		"conservacion" => "ofertas.conservacion",
		"key_tipo"     => "ofertas.key_tipo",
		"keyacci"      => "ofertas.keyacci"
	];
	const ACTION_VENTA = 1;
	const ACTION_ALQUILER = 2;
	const ACTION_TRASPASO = 3;
	const ACTION_VENTA_ALQUILER = 4;
	const ACTION_VENTA_TRASPASO = 5;
	const ACTION_ALQUILER_TRASPASO = 6;
	const ACTION_VENTA_ALQUILER_TRASPASO = 7;
	const ACTION_FUERA_MERCADO = 8;
	const ACTION_ALQUILER_TEMPORADA = 9;
	const OPERATION_EQUAL = "=";
	const OPERATION_DISTINC = "!=";
	const OPERATION_GREAT = ">";
	const OPERATION_LESS = "<";
	const OPERATION_GREAT_EQUAL = ">=";
	const OPERATION_lESS_EQUAL = "<=";
	const OPERATION_LIKE = " LIKE ";
	const MAX_EMENTS = 50;
	const MAX_EMENTS_IMPORTANTS = 30;

	private $where = [];
	private $maxElements = null;

	/**
	 * Properties constructor.
	 *
	 * @param int $maxElements
	 */
	public function __construct( Server $connexion, $maxElements = self::MAX_EMENTS ) {
		parent::__construct( $connexion );
		$this->maxElements = $maxElements;

	}

	private function validateWhere( $key, $value ) {
		if ( in_array( $key, array_keys( self::WHERE_FIELDS ) ) ) {
			$value  = explode( ',', $value );
			$filter = self::WHERE_FIELDS[ $key ];
			switch ( $filter ) {
				case 1:
					foreach ( $value as $item ) {
						if ( ! is_numeric( $item ) ) {
							return false;
						}
					}
					break;
				case "":
					foreach ( $value as $item ) {
						if ( ! is_string( $item ) ) {
							return false;
						}
					}
					break;
				default:
					foreach ( $value as $item ) {
						if ( array_search( $item, $filter ) === false ) {
							return false;
						}
					}
					break;
			}
		}

		return true;
	}

	public function addWhere( $key, $value, $operacion = self::OPERATION_EQUAL ) {
		if ( $this->validateWhere( $key, $value ) ) {
			$this->where[ $operacion ][ $key ] = $value;
		}
	}

	private function merge( $operation, $key, $value ) {
		$string = '';
		$filter = self::WHERE_FIELDS[ $key ];
		if ( in_array( $key, array_keys( self::WHERE_CHANGES ) ) ) {
			$key = self::WHERE_CHANGES[ $key ];
		}
		if ( is_array( $filter ) ) {
			$filter = array_shift( $filter );
		}
		$value  = explode( ',', $value );
		$string = [];
		foreach ( $value as $item ) {
			if ( is_integer( $filter ) ) {
				$string[] = $key . $operation . $item;
			} else {
				$string[] = $key . $operation . "'" . str_replace( '%', '-caralike-', $item ) . "'";
			}
		}

		return "(" . implode( ' or ', $string ) . ")";
	}

	public function getNewsProperties( $num_elements = 10 ) {
		return $this->callSearchProperties( "paginacion", 1, $num_elements, 'and', $this->maxElements, 'fechacambio',
			'desc' );
	}

	public function searchProperties(
		$offset = 1,
		$num_elements = self::MAX_EMENTS,
		$merge = 'AND',
		$sort = 'ref',
		$sortDirection = 'asc'
	) {
		return $this->callSearchProperties( "paginacion", $offset, $num_elements, $merge, $this->maxElements, $sort,
			$sortDirection );
	}

	public function searchImportantProperties(
		$offset = 1,
		$num_elements = self::MAX_EMENTS_IMPORTANTS,
		$merge = 'AND',
		$sort = 'ref',
		$sortDirection = 'asc'
	) {
		return $this->callSearchProperties( "destacados", $offset, $num_elements, $merge, $this->maxElements, $sort,
			$sortDirection );
	}

	/**
	 * @param $function
	 * @param $offset
	 * @param $num_elements
	 * @param $merge
	 * @param $max_elements
	 *
	 * @return array
	 */
	private function callSearchProperties(
		$function,
		$offset,
		$num_elements,
		$merge,
		$max_elements,
		$sort,
		$sortDirection
	) {
		if ( $num_elements > $max_elements || $num_elements < 0 ) {
			$num_elements = $max_elements;
		}
		if ( $offset < 1 ) {
			$offset = 1;
		}

		$output = [];
		foreach (
			$this->getData( $function, $this->merge_where( $merge ), $offset,
				$num_elements, $sort, $sortDirection ) as $value
		) {
			$output[] = new Property( $this->connexion, $value );
		}
		$this->var = $output;

		return $this;
	}

	public function countElements( $merge = 'AND' ) {
		return $this->getMeta( "paginacion", $this->merge_where( $merge ), 1, $this->maxElements, 'ref',
			'asc' )['total'];
	}

	/**
	 * @return array
	 */
	private function merge_where( $merge ) {
		$where_string = [];
		foreach ( $this->where as $operation => $par ) {
			foreach ( $par as $key => $value ) {
				$where_string[] = $this->merge( $operation, $key, $value );
			}

		}
		$this->where = [];

		return implode( " " . $merge . " ", $where_string );
	}
}