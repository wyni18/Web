import org.jdom.*;
import org.jdom.input.SAXBuilder;
import org.json.*;

import java.io.*;
import java.net.*;
import java.util.List;
import java.text.*;

import javax.servlet.*;
import javax.servlet.http.*;
import javax.servlet.http.*;

/**
 * Servlet implementation class for Servlet: IMDB_Servlet
 *
 */
 public class HelloWorldExample extends javax.servlet.http.HttpServlet implements javax.servlet.Servlet {
  
	protected void doGet(HttpServletRequest request, HttpServletResponse response) 
		throws ServletException, IOException {
		// TODO Auto-generated method stub
		String urlString = "http://cs-server.usc.edu:14295/searchMusic.php?title=";
		request.setCharacterEncoding("UTF-8");
		urlString += URLEncoder.encode(request.getParameter("title"),"UTF-8");
		urlString += "&type=";
		urlString += request.getParameter("type");
		
		response.setContentType("text/html;charset=utf-8");
		
        PrintWriter out = response.getWriter();
		
		URL url = new URL(urlString);
		URLConnection urlConnection = url.openConnection();
		urlConnection.setRequestProperty("Accept-Charset", "UTF-8");
		urlConnection.setAllowUserInteraction(false);
		InputStream urlStream = url.openStream();
		
        SAXBuilder builder = new SAXBuilder();
        try {
			
        	Document document = (Document) builder.build(urlStream);
            Element root = document.getRootElement();
            
            if(root.getAttributeValue("total").equals("0")) {
            	JSONArray jsonArray = new JSONArray();
            	JSONObject jsonObj = new JSONObject();
                jsonObj.put("result", jsonArray);
                JSONObject jsonRoot = new JSONObject();
                jsonRoot.put("results", jsonObj);
                out.print(jsonRoot.toString());
                return;
            }
  
            JSONArray jsonArray = new JSONArray();
            
            List list = root.getChildren();
			Element node1 = (Element) list.get(0);
			
			if(root.getAttributeValue("total").equals("1")) {
			for (int i = 0; i < list.size(); i++) {
				Element node = (Element) list.get(i);
				JSONObject jsonobject = new JSONObject();	
            
				jsonobject.put("cover", node.getAttributeValue("cover"));
				jsonobject.put("name", node.getAttributeValue("name"));
            	jsonobject.put("year", node.getAttributeValue("year"));
            	jsonobject.put("genre", node.getAttributeValue("genre"));
            	jsonobject.put("detail", node.getAttributeValue("detail"));
     		   
            	jsonArray.put(jsonobject);
      
     		}
			}
			else if(root.getAttributeValue("total").equals("2")) {
            for (int i = 0; i < list.size(); i++) {
            	Element node = (Element) list.get(i);
            	JSONObject jsonobject = new JSONObject();		
            	
            	jsonobject.put("cover", node.getAttributeValue("cover"));
            	jsonobject.put("title", node.getAttributeValue("title"));
            	jsonobject.put("artist", node.getAttributeValue("artist"));
            	jsonobject.put("genre", node.getAttributeValue("genre"));
            	jsonobject.put("detail", node.getAttributeValue("detail"));
            	jsonobject.put("year", node.getAttributeValue("year"));
     		   
            	jsonArray.put(jsonobject);
      
     		}
			}
			else if(root.getAttributeValue("total").equals("3")) {
            for (int i = 0; i < list.size(); i++) {
            	Element node = (Element) list.get(i);
            	JSONObject jsonobject = new JSONObject();		
            	
            	jsonobject.put("sample", node.getAttributeValue("sample"));
            	jsonobject.put("title", node.getAttributeValue("title"));
            	jsonobject.put("performer", node.getAttributeValue("performer"));
            	jsonobject.put("composer", node.getAttributeValue("composer"));
            	jsonobject.put("detail", node.getAttributeValue("detail"));
            	
            	jsonArray.put(jsonobject);
      
     		}
			}
            JSONObject jsonObj = new JSONObject();
            jsonObj.put("result", jsonArray);
            JSONObject jsonRoot = new JSONObject();
            jsonRoot.put("results", jsonObj);
            out.print(jsonRoot.toString());
            
        }
        catch(IOException io)
        {
        	out.println(io.getMessage());
        }
        catch(JDOMException jdomex)
        {
        	out.println(jdomex.getMessage());
        	out.println("jdomError");
        }
       
       
        response.setStatus(200);
	}  	
		    
}
