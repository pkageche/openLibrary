<?php
/**
 * Created by goku.
 * User: goku
 * Date: 15/09/13
 * Time: 15:23
 * Licence : LGPL for any purpose
 * Documentation : https://gitlab.com/songoo/opensourelibraries/wikis/NetteHelpers
 */

/**
 * Class NetteDatabaseHelper
 *
 */
class NetteDatabaseHelper {
    /**
     * Parse basic where string to nette selection form, and add selection variables from dynamic count of parameters
     * @param NConnection $netteSelection
     * @param String $whereStatement
     * + dynamic count of parameters for selection variables
     * @return NConnection
     */
    static function  where($netteSelection,$whereStatement){
        $whereStatement = str_replace('"',"'",$whereStatement);
        $indexOfFirstDynamicParameter = 2;
        $numberOfAllPassedArguments = func_num_args();
        /*NDebugger::fireLog($numberOfAllPassedArguments);*/
        for($i = $indexOfFirstDynamicParameter ; $i < $numberOfAllPassedArguments; $i++) {
            NDebugger::fireLog(func_get_arg($i));
            $whereVariables[]=func_get_arg($i);
        }
        preg_match_all("/(['][^'^\s]*[?][^']*['])|[\s]([?])[\s]|[\s]([?])/",$whereStatement,$StatementSettingsForVariables);
        foreach($StatementSettingsForVariables[0] as $StatementSettingsForVariable){
            $whereVariablesAditionalSettings[]=$StatementSettingsForVariable;
        }
        /*NDebugger::fireLog($whereVariables);
        NDebugger::fireLog($whereVariablesAditionalSettings);*/
        $whereStatement = preg_replace("/(['][^'^\s]*[?][^']*['])|[\s]([?])[\s]|[\s]([?])/"," ? ",$whereStatement);
        $whereParametersWithAditionalSettingsSet[0]=$whereStatement;
        for($i=0;$i<count($whereVariablesAditionalSettings);$i++){
            $whereParametersWithAditionalSettingsSet[$i+1] = str_replace("?",$whereVariables[$i],$whereVariablesAditionalSettings[$i]); //edit where parameters
            $whereParametersWithAditionalSettingsSet[$i+1]=str_replace("'","",$whereParametersWithAditionalSettingsSet[$i+1]);
        }

        /* NDebugger::fireLog("whereStatement");
         NDebugger::fireLog($whereStatement);
         NDebugger::fireLog($whereParametersWithAditionalSettingsSet);*/

        //call native nette database where method, with array as parameters entry
        call_user_func_array(array($netteSelection, 'where'), $whereParametersWithAditionalSettingsSet /*array("customer.name = ? and customer.name like ? and customer.name like ? and customer.name like ? and customer.name like ? ",	" 2 ","3%","%4%"," 5 "," data ")*/);
        //$netteSelection->where("customer.name = ? and customer.name like ? and customer.name like ? and customer.name like ? and customer.name like ? ",	" 2 ","3%","%4%"," 5 "," data ");
        //NDebugger::$maxLen=2000;
        /*NDebugger::fireLog($netteSelection->getSql());*/
        return $netteSelection;
    }
}