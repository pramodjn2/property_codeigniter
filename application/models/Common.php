<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Common extends CI_Model {

	

	public function update($mytable,$data,$where)

	{

		if(is_array($where)){

			foreach ($where as $key => $value){

			  $this->db->where($key, $value);

			}

		} 

		$this->db->update($mytable, $this->db->escape_str($data));

		return true;

	}

	

	/* insert the register form in datebase */

	public function data_insert($table,$data){

		$query = $this->db->insert($table, $data); 

		$id = $this->db->insert_id();

		return $id;

	}

	/* select query */

    function select($table, $where ='',$coloumn = '*'){

        $sql = "SELECT $coloumn FROM $table";

		if(!empty($where)){

		   $sql .= " $where";

		}

		$query = $this->db->query($sql);

        if($query->num_rows()>0){

            return $query->result_array();

        }else{

            return false;

        }

    }

	

	function generateSelectBox($table, $oData, $select='', $where='') {

        $option = '';	

        $this->db->from($table);

		if(isset($where) && $where !='' && is_array($where)){

			$this->db->where($where);

		}

        $this->db->where('status', 'Active');

        $result = $this->db->get()->result_array();

        if ( is_array($result) && count($result) > 0 ) {			

			foreach($result as $value){

				if(is_array($select)){

					$selected = (in_array($value[$oData['value']],$select))?'SELECTED':'';

				}else{

					$selected = ($select==$value[$oData['value']])?'SELECTED':'';

				}

				

				$option .= '<option '.$selected.' value="'.$value[$oData['value']].'">'.$value[$oData['option']].'</option>'."\n";			

			}

        }

		

		return $option;

		

    }

	

	function generateCheckBox($table, $oData, $select='', $where='', $name='') {

        $option = '';	

        $this->db->from($table);

		if(isset($where) && $where !='' && is_array($where)){

			$this->db->where($where);

		}

        $this->db->where('status', 'Active');

        $result = $this->db->get()->result_array();

        if ( is_array($result) && count($result) > 0 ) {			

			foreach($result as $value){

				if(is_array($select)){

					$selected = (in_array($value[$oData['value']],$select))?'CHECKED':'';

				}else{

					$selected = ($select==$value[$oData['value']])?'CHECKED':'';

				}

				

				$option .= '<label class="checkbox-inline">

							<input type="checkbox" class="square-red" name="'.$name.'[]" value="'.$value[$oData['value']].'" '.$selected.'>'.$value[$oData['option']].'</label>';

			}

        }else{

			$option = 'Speciality Data not found';

		}

		

		return $option;

		

    }

	

	function generateFilterCheckBox($table, $oData, $select='', $where='', $name='',$row=12) {

        $option = '';	

        $this->db->from($table);

		if(isset($where) && $where !='' && is_array($where)){

			$this->db->where($where);

		}

        $this->db->where('status', 'Active');

        $result = $this->db->get()->result_array();

        if ( is_array($result) && count($result) > 0 ) {

			$option = '<div class="rowX">';

			$i=0;		

			foreach($result as $value){ $i++;

				

				if($i==4){

					$option .= '</div><div class="row border-top hiddenMoreOptions" style="display:none;">';

					//$i=0;

				}

				

				if(is_array($select)){

					$selected = (in_array($value[$oData['value']],$select))?'CHECKED':'';

				}else{

					$selected = ($select==$value[$oData['value']])?'CHECKED':'';

				}

								

				$option .= '<label class="checkbox-inline no-margin-right col-md-'.$row.' col-sm-'.$row.'"><input type="checkbox" class="red" name="'.$name.'[]" value="'.$value[$oData['value']].'" '.$selected.'>'.$value[$oData['option']].'</label>';

			}

			$option .= '</div>';	

        }else{

			$option = 'Data not found';

		}

		

		return $option;

		

    }

	

	

	  function select_specialties($table, $where ='',$coloumn = '*'){

        $sql = "SELECT $coloumn FROM $table";

		if(!empty($where)){

		   $sql .= " $where";

		}

		$query = $this->db->query($sql);

        if($query->num_rows()>0){

            foreach($query->result_array() as $val){

				$sp[] = $val['specialties_id'];

				}

				return $sp;

        }else{

            return false;

        }

    }

	

    

	function count($table, $where =''){

        $sql = "SELECT COUNT(*) FROM $table";

		if(!empty($where)){

		   $sql .= " $where";

		}

		$query = $this->db->query($sql);

       

		if($query->num_rows()> 0){

			return (int)$query->row(0)->{'COUNT(*)'};	

        }else{

            return 0;

        }

    }

	

	function getCountryListBox($country_id = ''){

	if($country_id == 0 || empty($country_id)){$country_id = 253;}

		

        $this->db->select("countryid, country", false);

		$this->db->from('country');

		$this->db->where('status', 'Active');

        $result = $this->db->get();

		$responseData = '';

        if ($result->num_rows() > 0 ) {			

			foreach($result->result_array() as $value){				

				$selected = ($value['countryid']==$country_id) ? 'SELECTED' : '';

				$responseData .= '<option '.$selected.' value="'.$value['countryid'].'">'.$value['country'].'</option>'."\n";			

			}

        }

				

		return $responseData;		

	}

	

	function getStateListBox($country_id='', $region_id=''){

		$responseData = '';

        if(!empty($country_id) && $country_id !=''){			

			$this->db->select("regionid, region", false);

			$this->db->from('country_regions');

			$this->db->where('status', 'Active');

			$this->db->where('countryid', $country_id);		

			$result = $this->db->get();			

			if ($result->num_rows() > 0 ) {			

				foreach($result->result_array() as $value){				

					$selected = ($value['regionid']==$region_id) ? 'SELECTED' : '';

					$responseData .= '<option '.$selected.' value="'.$value['regionid'].'">'.$value['region'].'</option>'."\n";			

				}

			}

		}

		return $responseData;		

	}

	

	function getCityListBox($regionID='', $city_id=''){		

        $responseData = '';

		if(!empty($regionID) && $regionID !=''){					

			$this->db->select("cityId, city");

			$this->db->from('country_region_cities');

			$this->db->where('status', 'Active');			

			$this->db->where('regionid', $regionID);		

			$result = $this->db->get();			

			if($result->num_rows() > 0 ) {			

				foreach($result->result_array() as $value){								

					$selected = ($value['cityId']==$city_id) ? 'SELECTED' : '';

					$responseData .= '<option '.$selected.' value="'.$value['cityId'].'">'.$value['city'].'</option>'."\n";			

				}

			}

		}

		return $responseData;		

	}

	

	function generateSelectWithCheckBox($table, $oData, $select='', $where='') {

        $option = '';	

        $this->db->from($table);

		if(isset($where) && $where !='' && is_array($where)){

			$this->db->where($where);

		}

        $this->db->where('status', 'Active');

        $result = $this->db->get()->result_array();

        if ( is_array($result) && count($result) > 0 ) {			

			foreach($result as $value){

				if(is_array($select)){

					$checked = (in_array($value[$oData['value']],$select))?'checked':'';

				}else{

					$checked = ($select==$value[$oData['value']])?'checked':'';

				}

				$option .='<li><a href="#" class="small" tabIndex="-1"><input type="checkbox" '.$checked.' name="Specialities[]" value="'.$value[$oData['value']].'"/>'.$value[$oData['option']].'</a></li>';

				//$option .= '<option '.$selected.' value="'.$value[$oData['value']].'">'.$value[$oData['option']].'</option>'."\n";			

			}

        }

		

		return $option;

		

    }
	
	function property_info_get($property_id){
		if(empty($property_id)){ return false;}
		 $this->db->select('p.user_id,p.property_id,p.property_name, p.address, p.country, p.zipcode, p.prices,p.bedrooms,p.auction_status,pc.categoryName,pty.typeName,pimg.image_name,u.firstName,u.lastName,u.email,u.profile_image',false);


	$this->db->from('property p');
	$this->db->join('user u', 'u.user_id = p.user_id', 'LEFT');
	$this->db->join('property_category pc', 'pc.property_category_id = p.property_category', 'LEFT');
	$this->db->join('property_types pty', 'pty.property_types_id = p.property_type', 'LEFT');
	$this->db->join('property_image pimg', 'pimg.property_id = p.property_id', 'LEFT');
    $this->db->where('p.property_id', $property_id); 
    $this->db->where('p.status', 'Active');
	$this->db->where('p.property_availability', 'available');
	$this->db->group_by("p.property_id");
	$query = $this->db->get();
	if($query->num_rows()>0){
		$result =  $query->result_array();
		 return  $result;
	}else{ 
	   return false;
	}
		
		}

}



