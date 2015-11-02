<?php
class flatdata {
    private $table_name = "users";
    private $id;
    private $data;
    private $table;
    private $table_id;
    private $user;
    private $users;
 
    public function __construct(){
    	  $this->path = 'data/'.$table_name.'/';
        $this->table    = $this->path.$table_name.'.json';
        $this->table_id = $this->path.$table_name.'_id.txt';
       // $this->credit = $this->path."credit.json";
 
        // si le fichier n'existe pas, on le crée
        if(!file_exists($this->table)){
            $this->createTable();
        }
    }

	// création des fichiers Json et txt
	// le fichier txt servira uniquement à l'incrémentation
	public function createTable(){
	    $handle    = @fopen($this->table, "a+");
	    $handle_id = @fopen($this->table_id, "a+");
	 //   $handle_credit = @fopen($this->credit, "a+");
	    if($handle && $handle_id && $handle_credit) {
	        fclose($handle);
	        fclose($handle_id);
	       // fclose($handle_credit);
	        return true;
	    }
	}
	
	// Ajout d'un nouvel utilisateur
	public function add($data){
	 
	    // info sur le nouvel user
	    $this->data  = $data;
	 
	    // on récupère le nouvel ID
	    $this->id = $this->getId();
	 
	    if( $this->id !== null ){
	        // on ajoute l'ID au nouvel user
	        $this->data = array('id'=>$this->id) + $this->data;
	 
	        // on ajoute le nouvel user au fichier
	        $file = @fopen($this->table, 'a+');
	        
	        
		    if($data['credit']){
			    
			    $credit =  array_merge (array('id'=>$this->id), array( 'credit' => $this->data['credit']));
			    
			    // on ajoute le nouvel user au fichier
			    $file_index_credit = @fopen($this->credit, 'a+');
			    
			    fputs($file_index_credit, json_encode($credit).',');
			    
			    fclose($file_index_creditfile);

	        
	        }
	 
	        fputs($file, json_encode($this->data).',');
	 
	        fclose($file);
	 
	        return true;
	    }else{
	        return "Erreur pour l'ajout de l'utilisateur.";
	    }
	 
	}
	
	// on récupère un nouvel ID
	private function getId(){
	 
	    if( $txt = @fopen($this->table_id, "r+") ){
	        $this->id = fgets( $txt ); // récupération de la valeur
	        $this->id = intval( $this->id ); // on vérifie qu’il s’agit bien d’un nombre
	        $this->id++; // on incrémente
	        fseek( $txt, 0 ); // réinitialisation du curseur
	        fputs( $txt, $this->id ); // on écrit le nouveau nombre
	        fclose($txt);
	 
	        return $this->id;
	    }else{
	        return null;
	    }
	}  
	
	// on récupère un user spécifique
	public function get($id){
	 
	    // l'id de l'user qu'on souhaite récupérer
	    $this->id = $id;
	 
	    // liste de tous les users
	    $datas = $this->getAllUser();
	 
	    // on teste si l'id correspond, si oui on renvoie le résultat
	    foreach ($datas as $key => $row) {
	        if($row->id == $id ) return $row;
	    }
	 
	    // sinon
	    return 'Aucun utilisateur ne correspond.';
	} 
	// liste de tous les users
	public function getAll(){
	 
	    // Si les users sont déjà définis
	    if (isset($this->users)) return $this->users;
	 
	    // on récupère le contenu du fichier
	    $contents    = file_get_contents($this->table);
	    $this->users = json_decode("[". substr($contents, 0, -1)."]");
	 
	    // on renvoi le tableau des users
	    return $this->users;
	}
	
	// modification d'un utilisateur
	public function update($id, $data){
	    $this->id    = $id;
	    $this->data  = $data;
	 
	    // liste de tous les users
	    $this->users = $this->getAllUser();
	 
	    // on ouvre et on vide le fichier
	    if($handle = @fopen($this->table, "w+")) {
	        $this->data = "";
	        foreach ($this->users as $key => $row) {
	            // si l'id correspond
	            // on modifie le nouveau pseudo par exemple
	            if( $row->id == $this->id ){
	                if($data['pseudo'] != "") $row->pseudo = $data['pseudo'];
	            }
	 
	            $this->data .= json_encode($row).',';
	        }
	        // on ajoute tous les utilisateurs dans le fichier
	        fputs($handle, $this->data);
	 
	        fclose($handle);
	        return true;
	    }
	} 
	
	// suppression d'un utilisateur
	public function remove($id){
	    $this->id    = $id;
	 
	    // liste tous les users
	    $this->users = $this->getAllUser();
	 
	    if($handle = @fopen($this->table, "w+")) {
	        $this->data = "";
	        foreach ($this->users as $key => $row) {
	            if($row->id == $this->id )
	                unset($this->users[$key]);
	            else
	                $this->data .= json_encode($row).',';
	        }
	 
	        fputs($handle, $this->data);
	 
	        fclose($handle);
	        return true;
	    }
	 
	}
	
}


?>
