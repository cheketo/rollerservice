<?php
    trait CoreCrud
    {
        public function Insert($Fields,$Values)
        {
            return Core::Insert(self::TABLE,$Fields,$Values);
        }
        
        public function Update($Values)
        {
            return Core::Update(self::TABLE,$Values,self::TABLE_ID."=".$_POST['id']);
        }
        
        public function Delete()
        {
            return Core::Update(self::TABLE,"status='I'",self::TABLE_ID."=".$_POST['id']);
        }
        
        public function Activate()
        {
            return Core::Update(self::TABLE,"status='A'",self::TABLE_ID."=".$_POST['id']);
        }
    }
    
?>